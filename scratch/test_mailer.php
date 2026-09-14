<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'abdulbahad.dev@gmail.com';
$otp = '337178';

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=bazaarwa_ps_abc_digital_agen_launchshop;charset=utf8mb4", 'root', 'root');
    $be = $pdo->query("SELECT * FROM basic_extendeds LIMIT 1")->fetch(PDO::FETCH_OBJ);

    echo "SMTP Host: " . ($be->smtp_host ?? 'NULL') . "\n";
    echo "SMTP User: " . ($be->smtp_username ?? 'NULL') . "\n";
    echo "SMTP Pass: " . ($be->smtp_password ?? 'NULL') . "\n";
    echo "SMTP Port: " . ($be->smtp_port ?? 'NULL') . "\n";
    echo "Encryption: " . ($be->encryption ?? 'NULL') . "\n";
    echo "From Mail: " . ($be->from_mail ?? 'NULL') . "\n";
    
    echo "Attempting Mail::send...\n";
    
    $smtp = [
        'driver' => 'smtp',
        'transport' => 'smtp',
        'host' => $be->smtp_host,
        'port' => $be->smtp_port,
        'encryption' => $be->encryption,
        'username' => $be->smtp_username,
        'password' => $be->smtp_password,
        'timeout' => 10,
    ];
    Config::set('mail.mailers.smtp', $smtp);
    Config::set('mail.default', 'smtp');
    Config::set('mail.from.address', $be->from_mail);
    Config::set('mail.from.name', 'Websitebuilder');

    \Illuminate\Support\Facades\Mail::raw("Your OTP verification code is {$otp} for Websitebuilder Ecommerce - Valid for 10 minutes.", function ($message) use ($email, $be) {
        $message->to($email)
                ->from($be->from_mail, 'Websitebuilder')
                ->subject('Your OTP Verification Code - Websitebuilder Ecommerce');
    });
    
    echo "MAIL SENT SUCCESSFULLY TO {$email}!\n";
} catch (Exception $e) {
    echo "ERROR SENDING MAIL: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
