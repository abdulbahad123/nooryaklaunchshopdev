<?php
require 'vendor/autoload.php';

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

try {
    $transport = new EsmtpTransport('mail.saasreselling.com', 465, true);
    $transport->setUsername('admin@saasreselling.com');
    $transport->setPassword('Admin@reselling');
    
    $mailer = new Mailer($transport);
    
    $email = (new Email())
        ->from('admin@saasreselling.com')
        ->to('abdulbahad.dev@gmail.com')
        ->subject('Test Message from saasreselling')
        ->text('This is a test message to verify SMTP settings after PTR update.');
        
    $mailer->send($email);
    
    echo "SUCCESS: Email sent to abdulbahad.dev@gmail.com\n";
} catch (\Exception $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
}
