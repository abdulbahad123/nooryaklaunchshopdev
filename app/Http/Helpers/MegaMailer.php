<?php

namespace App\Http\Helpers;

use App\Models\BasicExtended;
use App\Models\EmailTemplate;
use App\Models\Language;
use App\Models\User\UserEmailTemplate;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class MegaMailer
{

    public function mailFromAdmin($data)
    {
        $temp = EmailTemplate::where('email_type', '=', $data['templateType'])->first();

        $body = $temp->email_body;
        if (array_key_exists('username', $data)) {
            $body = preg_replace("/{username}/", $data['username'], $body);
        }
        if (array_key_exists('replaced_package', $data)) {
            $body = preg_replace("/{replaced_package}/", $data['replaced_package'], $body);
        }
        if (array_key_exists('removed_package_title', $data)) {
            $body = preg_replace("/{removed_package_title}/", $data['removed_package_title'], $body);
        }
        if (array_key_exists('package_title', $data)) {
            $body = preg_replace("/{package_title}/", $data['package_title'], $body);
        }
        if (array_key_exists('package_price', $data)) {
            $body = preg_replace("/{package_price}/", $data['package_price'], $body);
        }
        if (array_key_exists('activation_date', $data)) {
            $body = preg_replace("/{activation_date}/", $data['activation_date'], $body);
        }
        if (array_key_exists('expire_date', $data)) {
            $body = preg_replace("/{expire_date}/", $data['expire_date'], $body);
        }
        if (array_key_exists('requested_domain', $data)) {
            $body = preg_replace("/{requested_domain}/", "<a href='http://" . $data['requested_domain'] . "'>" . $data['requested_domain'] . "</a>", $body);
        }
        if (array_key_exists('previous_domain', $data)) {
            $body = preg_replace("/{previous_domain}/", "<a href='http://" . $data['previous_domain'] . "'>" . $data['previous_domain'] . "</a>", $body);
        }
        if (array_key_exists('current_domain', $data)) {
            $body = preg_replace("/{current_domain}/", "<a href='http://" . $data['current_domain'] . "'>" . $data['current_domain'] . "</a>", $body);
        }
        if (array_key_exists('subdomain', $data)) {
            $body = preg_replace("/{subdomain}/", "<a href='http://" . $data['subdomain'] . "'>" . $data['subdomain'] . "</a>", $body);
        }
        if (array_key_exists('last_day_of_membership', $data)) {
            $body = preg_replace("/{last_day_of_membership}/", $data['last_day_of_membership'], $body);
        }
        if (array_key_exists('login_link', $data)) {
            $body = preg_replace("/{login_link}/", $data['login_link'], $body);
        }
        if (array_key_exists('customer_name', $data)) {
            $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
        }
        if (array_key_exists('verification_link', $data)) {
            $body = preg_replace("/{verification_link}/", $data['verification_link'], $body);
        }
        if (array_key_exists('website_title', $data)) {
            $body = preg_replace("/{website_title}/", $data['website_title'], $body);
        }

        if ($data['templateType'] == 'email_verification' && array_key_exists('password', $data)) {
            $login_link = array_key_exists('login_link', $data) ? $data['login_link'] : route('user.login');
            $plan_info = '';
            if (array_key_exists('package_title', $data) && !empty($data['package_title'])) {
                $plan_info = '<p style="margin: 0 0 6px 0; font-size: 14px; color: #334155;"><strong>Purchased Plan:</strong> ' . htmlspecialchars($data['package_title']) . '</p>';
            }
            $credentials_card = '
            <div style="margin-top: 30px; padding: 20px; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <h4 style="margin: 0 0 12px 0; color: #0f172a; font-size: 15px; font-weight: 700;">Your Account Credentials & Plan Details</h4>
                ' . $plan_info . '
                <p style="margin: 0 0 6px 0; font-size: 14px; color: #334155;"><strong>Email/Username:</strong> ' . $data['toMail'] . '</p>
                <p style="margin: 0 0 16px 0; font-size: 14px; color: #334155;"><strong>Password:</strong> ' . $data['password'] . '</p>
                <a href="' . $login_link . '" style="display: inline-block; padding: 10px 18px; font-size: 13px; font-weight: bold; color: #ffffff; background-color: #0f172a; border-radius: 6px; text-decoration: none;">Login to Your Account</a>
            </div>';
            $body .= $credentials_card;
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $be = $currentLang->basic_extended;

        if ($be->is_smtp == 1) {
            try {
                //config smtp
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $be->smtp_host,
                    'port' => $be->smtp_port,
                    'encryption' => $be->encryption,
                    'username' => $be->smtp_username,
                    'password' => $be->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
                //set data to for pass in te mail array
                $mailData = [];
                $mailData['from_mail'] = $be->from_mail;
                $mailData['toMail'] = $data['toMail'];
                $mailData['subject'] = $temp->email_subject;
                $mailData['body'] = Common::wrapEmailBody($body, $temp->email_subject);
                if (array_key_exists('membership_invoice', $data)) {
                    $mailData['membership_invoice'] = $data['membership_invoice'];
                }
                //send mail
                Mail::send([], [], function (Message $message) use ($mailData) {
                    $message->to($mailData['toMail'])
                        ->from($mailData['from_mail'])
                        ->subject($mailData['subject'])
                        ->html($mailData['body'], 'text/html');

                    if (array_key_exists('membership_invoice', $mailData)) {
                        $filePath = public_path('assets/front/invoices/') . $mailData['membership_invoice'];

                        if (file_exists($filePath)) {
                            $message->attach($filePath);
                        }
                    }
                });
                // Attachments
                if (array_key_exists('membership_invoice', $mailData)) {
                    @unlink(public_path('assets/front/invoices/') . $mailData['membership_invoice']);
                }
            } catch (TransportException $e) {
                // Attachments
                if (array_key_exists('membership_invoice', $mailData)) {
                    @unlink(public_path('assets/front/invoices/') . $mailData['membership_invoice']);
                }
                Session::flash('error', 'Mail could not be sent.');
                return;
            }
        }
    }

    public function mailFromUser($data)
    {
        $user = getUser();
        $temp = UserEmailTemplate::where('email_type', '=', $data['templateType'])->where('user_id', $user->id)->first();
        if ($temp) {
            $body = $temp->email_body;
            if (array_key_exists('username', $data)) {
                $body = preg_replace("/{username}/", $data['username'], $body);
            }
            if (array_key_exists('customer_name', $data)) {
                $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
            }
            if (array_key_exists('order_number', $data)) {
                $body = preg_replace("/{order_number}/", $data['order_number'], $body);
            }
            if (array_key_exists('order_link', $data)) {
                $body = preg_replace("/{order_link}/", $data['order_link'], $body);
            }

            if (array_key_exists('website_title', $data)) {
                $body = preg_replace("/{website_title}/", $data['website_title'], $body);
            }

            if (session()->has('lang')) {
                $currentLang = Language::where('code', session()->get('lang'))->first();
            } else {
                $currentLang = Language::where('is_default', 1)->first();
            }

            $be = $currentLang->basic_extended;

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';


            if ($be->is_smtp == 1) {
                try {

                    $mail->isSMTP();
                    $mail->Host       = $be->smtp_host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $be->smtp_username;
                    $mail->Password   = $be->smtp_password;
                    $mail->SMTPSecure = $be->encryption;
                    $mail->Port       = $be->smtp_port;
                } catch (Exception $e) {
                }
            }

            try {

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($data['toMail'], $data['toName']);

                // Attachments
                if (array_key_exists('order_number', $data)) {
                    $mail->addAttachment('assets/front/invoices/' . $data['attachment']);
                }

                // Content
                $mail->isHTML(true);
                $mail->Subject = $temp->email_subject;
                $mail->Body    = Common::wrapEmailBody($body, $temp->email_subject, $user);

                $mail->send();
            } catch (Exception $e) {
            }
        }
    }

    public function mailToAdmin($data)
    {
        $be = BasicExtended::first();
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {

                $mail->isSMTP();
                $mail->Host = $be->smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $be->smtp_username;
                $mail->Password = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port = $be->smtp_port;
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
            }
        }
        try {
            $mail->setFrom($data['fromMail'], $data['fromName']);
            $mail->addAddress($be->from_mail);     // Add a recipient

            // Attachments
            if (array_key_exists('attachments', $data)) {
                $mail->addAttachment('front/invoices/' . $data['attachments']); // Add attachments
            }

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = $data['subject'];
            $mail->Body = Common::wrapEmailBody($data['body'], $data['subject']);

            $mail->send();
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
    }
    public function mailContactMessage($data)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang->basic_extended;
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }

        try {
            //Recipients
            $mail->setFrom($be->from_mail, $be->from_name);
            $mail->addAddress($data['toMail'], $data['toName']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body    = Common::wrapEmailBody($data['body'], $data['subject']);
            $mail->send();
        } catch (Exception $e) {
            Session::flash('error', $e);
            return back();
        }
    }

    /**
     * Send a beautifully formatted welcome email containing credentials and plan details.
     */
    public function sendWelcomeCredentialsEmail($user, $password, $planName, $planPrice)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang->basic_extended;
        $bs = $currentLang->basic_setting;

        $storeLiveLink = '';
        $host = request()->getHost();
        if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
            $storeLiveLink = 'http://' . $user->username . '.localhost:8000';
        } else {
            $storeLiveLink = 'https://' . $user->username . '.' . $host;
        }
        $loginLink = route('user.login');

        // Build premium HTML email template inline
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; color: #1e293b; }
                .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e2e8f0; }
                .header { background: linear-gradient(135deg, #ff5a2c, #ff8c00); padding: 30px; text-align: center; color: #ffffff; }
                .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
                .content { padding: 30px; }
                .welcome-msg { font-size: 16px; line-height: 1.6; color: #334155; margin-top: 0; }
                .info-box { background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 24px; }
                .info-row { display: flex; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
                .info-row:last-child { border-bottom: none; }
                .info-label { width: 140px; font-weight: 700; color: #475569; font-size: 14px; }
                .info-value { flex: 1; color: #0f172a; font-size: 14px; word-break: break-all; }
                .btn-group { text-align: center; margin-top: 10px; }
                .btn { display: inline-block; padding: 12px 24px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 14px; transition: transform 0.2s; }
                .btn-primary { background: #ff5a2c; color: #ffffff !important; margin-right: 10px; }
                .btn-secondary { background: #0f172a; color: #ffffff !important; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="header">
                    <h1>🎉 Welcome to ' . htmlspecialchars($bs->website_title) . '!</h1>
                </div>
                <div class="content">
                    <p class="welcome-msg">Hi <strong>' . htmlspecialchars($user->first_name) . '</strong>,</p>
                    <p class="welcome-msg">Your online store has been successfully created. Below are your store details, login credentials, and links to get started:</p>
                    
                    <div class="info-box">
                        <div class="info-row">
                            <div class="info-label">👤 Store Name:</div>
                            <div class="info-value">' . htmlspecialchars($user->shop_name) . '</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">👤 Username:</div>
                            <div class="info-value">' . htmlspecialchars($user->username) . '</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">📧 Email:</div>
                            <div class="info-value">' . htmlspecialchars($user->email) . '</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">📞 Phone:</div>
                            <div class="info-value">' . htmlspecialchars($user->phone) . '</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">🔑 Password:</div>
                            <div class="info-value"><code>' . htmlspecialchars($password) . '</code></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">📦 Plan:</div>
                            <div class="info-value">' . htmlspecialchars($planName) . ($planPrice ? ' (' . $planPrice . ')' : '') . '</div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <a href="' . $storeLiveLink . '" target="_blank" class="btn btn-primary">🔗 Visit Live Store</a>
                        <a href="' . $loginLink . '" target="_blank" class="btn btn-secondary">🔗 Login Dashboard</a>
                    </div>
                </div>
                <div class="footer">
                    Need help? Chat with us anytime.<br>
                    &copy; ' . date('Y') . ' ' . htmlspecialchars($bs->website_title) . '. All rights reserved.
                </div>
            </div>
        </body>
        </html>
        ';

        if ($be->is_smtp == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $be->smtp_host,
                    'port' => $be->smtp_port,
                    'encryption' => $be->encryption,
                    'username' => $be->smtp_username,
                    'password' => $be->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);

                $mailData = [
                    'from_mail' => $be->from_mail,
                    'from_name' => $be->from_name ?? $bs->website_title,
                    'toMail' => $user->email,
                    'subject' => '🎉 Welcome to ' . $bs->website_title . '! Your Store is Ready',
                    'body' => $html
                ];

                Mail::send([], [], function (Message $message) use ($mailData) {
                    $message->to($mailData['toMail'])
                        ->from($mailData['from_mail'], $mailData['from_name'])
                        ->subject($mailData['subject'])
                        ->html($mailData['body'], 'text/html');
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('SMTP Welcome credentials email failed: ' . $e->getMessage());
            }
        }
    }
}

