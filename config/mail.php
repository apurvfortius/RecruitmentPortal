<?php
return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port' => env('MAIL_PORT', 587),
    'from' => ['address' => env('MAIL_FROM', "fortius.developer@gmail.com"), 'name' => env('MAIL_FROM_NAME', "Fortius Tech Solutions")],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
   
    'from_address' => env('MAIL_FROM'),
    'from_name' => env('MAIL_FROM_NAME'),

];
