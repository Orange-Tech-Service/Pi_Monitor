<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@app/mail',
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'mail.example.com', // Replace with your mail server host
        'username' => 'my-username@example.com', // Replace with your own user name
        'password' => 'my-password', // Replace with your own user password
        'port' => '465',
        'encryption' => 'ssl',
    ],
    /*
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'localhost',
            'username' => 'username',
            'password' => 'password',
            'port' => '587',
            'encryption' => 'tls',
        ],

        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'localhost',
            'port' => '25',
        ],

        'transport' => [
            'class' => 'Swift_SendmailTransport',
        ],
     */
];