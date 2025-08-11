<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DualEmailMFAService
{
  private $mfaEmails = [
    'careers@fidenz.com',
    'yehanlakvindurcg@gmail.com'
  ];

  public function sendMFACodeToBothEmails($user, $code)
  {
    Log::info('📧 Sending MFA code to both emails', [
      'user' => $user['email'] ?? 'unknown',
      'target_emails' => $this->mfaEmails,
      'code' => substr($code, 0, 2) . '****'
    ]);

    $results = [];

    foreach ($this->mfaEmails as $email) {
      try {
        $this->sendMFAEmail($email, $code, $user);
        $results[$email] = 'sent';
        Log::info("✅ MFA code sent to {$email}");
      } catch (\Exception $e) {
        $results[$email] = 'failed';
        Log::error("❌ Failed to send MFA code to {$email}: " . $e->getMessage());
      }
    }

    // Cache the code for validation
    Cache::put("mfa_code_{$code}", [
      'user' => $user,
      'emails' => $this->mfaEmails,
      'timestamp' => now()
    ], 300); // 5 minutes

    return $results;
  }

  private function sendMFAEmail($email, $code, $user)
  {
    // For demo purposes, we'll log the email content
    // In production, you'd use actual email sending

    $emailContent = [
      'to' => $email,
      'subject' => 'Weather App - Verification Code',
      'code' => $code,
      'user' => $user['email'] ?? 'User',
      'timestamp' => now()->format('Y-m-d H:i:s')
    ];

    Log::info('📨 MFA Email Content', $emailContent);

    // Simulate email sending (replace with actual Mail::send in production)
    return true;
  }

  public function generateMFACode()
  {
    return sprintf('%06d', mt_rand(100000, 999999));
  }

  public function validateMFACode($code)
  {
    $cacheKey = "mfa_code_{$code}";
    $codeData = Cache::get($cacheKey);

    if ($codeData) {
      Cache::forget($cacheKey);
      Log::info('✅ MFA code validated successfully', ['code' => substr($code, 0, 2) . '****']);
      return $codeData;
    }

    Log::warning('❌ Invalid or expired MFA code', ['code' => substr($code, 0, 2) . '****']);
    return false;
  }
}
