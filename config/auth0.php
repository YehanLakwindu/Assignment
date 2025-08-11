<?php

return [
  'domain' => env('AUTH0_DOMAIN'),
  'clientId' => env('AUTH0_CLIENT_ID'),
  'clientSecret' => env('AUTH0_CLIENT_SECRET'),
  'redirectUri' => env('AUTH0_REDIRECT_URI'),
  'cookieSecret' => env('AUTH0_COOKIE_SECRET'),
  // Only include audience if it's properly configured in Auth0
  // 'audience' => env('AUTH0_AUDIENCE'),
];
