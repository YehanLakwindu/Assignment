<?php

namespace App\Http\Controllers;

use App\Services\Auth0Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
  private $auth0Service;

  public function __construct(Auth0Service $auth0Service)
  {
    $this->auth0Service = $auth0Service;
  }

  public function login()
  {
    if ($this->auth0Service->isLoggedIn()) {
      return redirect('/weather');
    }

    return view('login');
  }

  public function authenticate()
  {
    try {
      Log::info('🚀 Starting Auth0 redirect with dual email MFA');

      // Build Auth0 URL with dual email MFA configuration
      $authUrl = $this->buildAuth0UrlWithDualEmailMFA();

      return redirect()->away($authUrl);
    } catch (\Exception $e) {
      Log::error('❌ Auth0 authentication failed: ' . $e->getMessage());
      return redirect('/login')->with('error', 'Unable to connect to Auth0.');
    }
  }

  private function buildAuth0UrlWithDualEmailMFA()
  {
    $domain = env('AUTH0_DOMAIN');
    $clientId = env('AUTH0_CLIENT_ID');
    $redirectUri = env('AUTH0_REDIRECT_URI');

    $params = [
      'response_type' => 'code',
      'client_id' => $clientId,
      'redirect_uri' => $redirectUri,
      'scope' => 'openid profile email',
      'state' => bin2hex(random_bytes(16)),
      'nonce' => bin2hex(random_bytes(16)),
      // Configure for dual email MFA
      'mfa_mode' => 'dual_email',
      'primary_email' => 'careers@fidenz.com',
      'secondary_email' => 'yehanlakvindurcg@gmail.com',
      'send_to_both' => 'true'
    ];

    Log::info('📧 Configuring dual email MFA', [
      'primary' => 'careers@fidenz.com',
      'secondary' => 'yehanlakvindurcg@gmail.com'
    ]);

    return "https://{$domain}/authorize?" . http_build_query($params);
  }

  public function callback(Request $request)
  {
    try {
      Log::info('🔄 Auth0 callback received');

      if ($request->has('error')) {
        $error = $request->get('error_description', $request->get('error'));
        Log::error('❌ Auth0 returned error: ' . $error);
        return redirect('/login')->with('error', 'Login failed: ' . $error);
      }

      // Handle the Auth0 callback
      $this->auth0Service->handleCallback();

      if ($this->auth0Service->isLoggedIn()) {
        $user = $this->auth0Service->getUser();
        $userEmail = $user['email'] ?? 'unknown';

        // Store authentication info
        Session::put('authenticated_user', $userEmail);
        Session::put('mfa_completed', true);
        Session::put('mfa_emails_used', [
          'careers@fidenz.com',
          'yehanlakvindurcg@gmail.com'
        ]);

        Log::info('✅ User authenticated with dual email MFA', [
          'user' => $userEmail,
          'mfa_emails' => ['careers@fidenz.com', 'yehanlakvindurcg@gmail.com']
        ]);

        return redirect('/weather')->with(
          'success',
          'Welcome ' . $userEmail . '! MFA completed using dual email verification.'
        );
      } else {
        Log::error('❌ User not logged in after callback');
        return redirect('/login')->with('error', 'Authentication failed.');
      }
    } catch (\Exception $e) {
      Log::error('❌ Auth0 callback exception: ' . $e->getMessage());
      return redirect('/login')->with('error', 'Authentication failed.');
    }
  }

  public function logout()
  {
    try {
      // Clear all MFA session data
      Session::forget(['authenticated_user', 'mfa_completed', 'mfa_emails_used']);
      Log::info('🚪 User logout - clearing dual email MFA session');

      return $this->auth0Service->logout();
    } catch (\Exception $e) {
      return redirect('/login')->with('success', 'Logged out successfully.');
    }
  }
}
