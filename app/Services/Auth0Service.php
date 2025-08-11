<?php

namespace App\Services;

use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Auth0Service
{
  private $auth0;
  private $dualEmailMFA;

  public function __construct(DualEmailMFAService $dualEmailMFA)
  {
    $this->dualEmailMFA = $dualEmailMFA;

    try {
      $configuration = new SdkConfiguration([
        'domain' => env('AUTH0_DOMAIN'),
        'clientId' => env('AUTH0_CLIENT_ID'),
        'clientSecret' => env('AUTH0_CLIENT_SECRET'),
        'redirectUri' => env('AUTH0_REDIRECT_URI'),
        'cookieSecret' => env('AUTH0_COOKIE_SECRET'),
        'scope' => ['openid', 'profile', 'email'],
      ]);

      $this->auth0 = new Auth0($configuration);
      Log::info('✅ Auth0 service initialized with dual email MFA support');
    } catch (\Exception $e) {
      Log::error('❌ Auth0 service initialization failed: ' . $e->getMessage());
      throw $e;
    }
  }

  public function isLoggedIn()
  {
    try {
      $user = $this->auth0->getUser();
      $isLoggedIn = $user !== null;

      if ($isLoggedIn) {
        Log::info('✅ User is logged in', ['email' => $user['email'] ?? 'no_email']);

        // Trigger dual email MFA if needed
        if (!Session::has('mfa_completed')) {
          $this->triggerDualEmailMFA($user);
        }
      }

      return $isLoggedIn;
    } catch (\Exception $e) {
      Log::error('❌ Auth0 isLoggedIn error: ' . $e->getMessage());
      return false;
    }
  }

  private function triggerDualEmailMFA($user)
  {
    try {
      $mfaCode = $this->dualEmailMFA->generateMFACode();

      Log::info('🔐 Triggering dual email MFA', [
        'user' => $user['email'] ?? 'unknown',
        'code_preview' => substr($mfaCode, 0, 2) . '****'
      ]);

      $results = $this->dualEmailMFA->sendMFACodeToBothEmails($user, $mfaCode);

      Session::put('pending_mfa_code', $mfaCode);
      Session::put('mfa_email_results', $results);

      return $results;
    } catch (\Exception $e) {
      Log::error('❌ Failed to trigger dual email MFA: ' . $e->getMessage());
      return false;
    }
  }

  public function login()
  {
    try {
      Log::info('🔐 Generating Auth0 login URL with dual email MFA');
      return $this->auth0->login();
    } catch (\Exception $e) {
      Log::error('❌ Auth0 login error: ' . $e->getMessage());
      throw $e;
    }
  }

  public function logout()
  {
    try {
      Log::info('🚪 Starting Auth0 logout process');
      Session::flush();
      return $this->auth0->logout(url('/'));
    } catch (\Exception $e) {
      Log::error('❌ Auth0 logout error: ' . $e->getMessage());
      return redirect('/login');
    }
  }

  public function getUser()
  {
    try {
      return $this->auth0->getUser();
    } catch (\Exception $e) {
      Log::error('❌ Auth0 getUser error: ' . $e->getMessage());
      return null;
    }
  }

  public function handleCallback()
  {
    try {
      Log::info('🔄 Processing Auth0 callback with dual email MFA');
      $result = $this->auth0->exchange();
      Log::info('✅ Auth0 callback processed successfully');
      return $result;
    } catch (\Exception $e) {
      Log::error('❌ Auth0 callback processing failed: ' . $e->getMessage());
      throw $e;
    }
  }
}
