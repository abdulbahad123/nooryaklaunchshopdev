<?php

namespace App\Http\Helpers;

use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Str;

class BasicMailer
{
  public static function sendMail($data)
  {
    $sent = false;
    if (isset($data['smtp_status']) && $data['smtp_status'] == 1) {
      $host = (!empty($data['smtp_host']) && !str_contains($data['smtp_host'], 'metroshop.in') && !str_contains($data['smtp_host'], 'nooryak.in'))
          ? $data['smtp_host']
          : env('MAIL_HOST', 'mail.saasreselling.com');
      $port = !empty($data['smtp_port']) ? (int)$data['smtp_port'] : (int)env('MAIL_PORT', 465);
      $encryption = !empty($data['encryption']) ? $data['encryption'] : env('MAIL_ENCRYPTION', 'ssl');
      $username = (!empty($data['smtp_username']) && !str_contains($data['smtp_username'], 'metroshop.in') && !str_contains($data['smtp_username'], 'nooryak.in'))
          ? $data['smtp_username']
          : env('MAIL_USERNAME', 'admin@saasreselling.com');
      $password = !empty($data['smtp_password']) ? $data['smtp_password'] : env('MAIL_PASSWORD', 'Admin@nooryak');

      $smtp = [
        'transport' => 'smtp',
        'host' => $host,
        'port' => $port,
        'encryption' => $encryption,
        'username' => $username,
        'password' => $password,
        'timeout' => 8,
        'auth_mode' => null,
      ];
      Config::set('mail.mailers.smtp', $smtp);
      Config::set('mail.default', 'smtp');
      Mail::purge('smtp');

      // add other informations and send the mail
      try {
        Mail::send([], [], function (Message $message) use ($data, $username) {
          $fromMail = (!empty($data['from_mail']) && !str_contains($data['from_mail'], 'metroshop.in') && !str_contains($data['from_mail'], 'nooryak.in'))
              ? $data['from_mail']
              : (!empty($username) ? $username : env('MAIL_FROM_ADDRESS', 'admin@saasreselling.com'));
          $fromName = !empty($data['from_name'])
              ? $data['from_name']
              : env('MAIL_FROM_NAME', 'Ecom Builder');
          $subject = $data['subject'] ?? 'Notification';
          $body = Common::wrapEmailBody($data['body'] ?? '', $subject, null, null, $fromName);
          $message->to($data['recipient'])
            ->from($fromMail, $fromName)
            ->replyTo($fromMail, $fromName)
            ->subject($subject)
            ->html($body, 'text/html');

          if (array_key_exists('invoice', $data)) {
            $message->attach($data['invoice']);
          }
        });
        $sent = true;
      } catch (\Exception $e) {
        Log::warning('BasicMailer SMTP failed: ' . $e->getMessage());
      }
    }

    // Fail-safe: Always fallback to PHP native mail() if SMTP was not sent or failed
    if (!$sent && !empty($data['recipient'])) {
      try {
        $fromMail = (!empty($data['from_mail']) && !str_contains($data['from_mail'], 'metroshop.in') && !str_contains($data['from_mail'], 'nooryak.in'))
            ? $data['from_mail']
            : config('mail.from.address', 'admin@saasreselling.com');
        $fromName = $data['from_name'] ?? config('mail.from.name', 'Ecom Builder');
        $subject  = $data['subject'] ?? 'Notification';
        $body     = Common::wrapEmailBody($data['body'] ?? '', $subject, null, null, $fromName);
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$fromName} <{$fromMail}>\r\n";
        $headers .= "Reply-To: {$fromName} <{$fromMail}>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        if (@mail($data['recipient'], $subject, $body, $headers)) {
          $sent = true;
        }
      } catch (\Throwable $e) {
        Log::warning('BasicMailer native mail fallback error: ' . $e->getMessage());
      }
    }

    return $sent;
  }
}
