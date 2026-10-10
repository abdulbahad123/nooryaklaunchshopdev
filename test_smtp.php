<?php
require 'vendor/autoload.php';
try {
    $transport = new Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport('mail.saasreselling.com', 465, true);
    $transport->setUsername('admin@saasreselling.com');
    $transport->setPassword('Admin@reselling');
    $transport->start();
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
}
