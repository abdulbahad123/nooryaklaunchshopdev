<?php

namespace App\Http\Helpers;

use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Str;

class BasicMailer
{
  public static function sendMail($data)
  {
    $sent = false;
    if (isset($data['smtp_status']) && $data['smtp_status'] == 1) {
      $smtp = [
        'transport' => 'smtp',
        'host' => $data['smtp_host'],
        'port' => $data['smtp_port'],
        'encryption' => $data['encryption'],
        'username' => $data['smtp_username'],
        'password' => $data['smtp_password'],
        'timeout' => 8,
        'auth_mode' => null,
      ];
      Config::set('mail.mailers.smtp', $smtp);
      Config::set('mail.default', 'smtp');
      \Illuminate\Support\Facades\Mail::purge('smtp');

      // add other informations and send the mail
      try {
        Mail::send([], [], function (Message $message) use ($data) {
          $fromMail = $data['from_mail'];
          $subject = $data['subject'];
          $body = Common::wrapEmailBody($data['body'], $subject);
          $message->to($data['recipient'])
            ->from($fromMail)
            ->subject($subject)
            ->html($body, 'text/html');

          if (array_key_exists('invoice', $data)) {
            $message->attach($data['invoice']);
          }
        });
        $sent = true;
      } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::warning('BasicMailer SMTP failed: ' . $e->getMessage());
      }
    }

    // Fail-safe: Always fallback to PHP native mail() if SMTP was not sent or failed
    if (!$sent && !empty($data['recipient'])) {
      try {
        $fromMail = $data['from_mail'] ?? config('mail.from.address', 'support@launchshop.in');
        $fromName = $data['from_name'] ?? config('mail.from.name', 'LaunchShop');
        $subject  = $data['subject'] ?? 'Notification';
        $body     = Common::wrapEmailBody($data['body'] ?? '', $subject);
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$fromName} <{$fromMail}>\r\n";
        $headers .= "Reply-To: {$fromMail}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        if (@mail($data['recipient'], $subject, $body, $headers)) {
          $sent = true;
        }
      } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('BasicMailer native mail fallback error: ' . $e->getMessage());
      }
    }

    return $sent;
  }
}
