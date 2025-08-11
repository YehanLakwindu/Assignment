<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth0Service;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class Auth0Middleware
{
    private $auth0Service;

    public function __construct(Auth0Service $auth0Service)
    {
        $this->auth0Service = $auth0Service;
    }

    public function handle(Request $request, Closure $next): Response
    {
        Log::info(' Auth0 middleware checking authentication for: ' . $request->url());

        if (!$this->auth0Service->isLoggedIn()) {
            Log::info(' User not authenticated, redirecting to login');
            return redirect('/login')->with('error', 'Please login to access this page');
        }

        Log::info('✅ User authenticated, proceeding');
        return $next($request);
    }
}
