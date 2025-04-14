# PHPMailerSendGrid
PHPMailer extension to send emails via Sendgrid (or save to file).

## Installation & loading

PHPMailerSendGrid is available on [Packagist](https://packagist.org/packages/rodrigoq/phpmailersendgrid) (using semantic versioning), and installation via [Composer](https://getcomposer.org) is the recommended way to install PHPMailer. Just add this line to your `composer.json` file:

```json
"rodrigoq/phpmailersendgrid": "^1.0"
```

or run

```sh
composer require rodrigoq/phpmailersendgrid
```

## Dependencies

This project depends on

* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
* [sendgrid-php](https://github.com/sendgrid/sendgrid-php)
* The [Sendgrid](https://sendgrid.com) service

## A Simple Example
```php
<?php

// Import PHPMailerSendGrid classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailerSendGrid;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

$mail = new PHPMailerSendGrid(true);                      // Passing `true` enables exceptions
try {
    //SendGrid settings
    $mail->isSendGrid();                                  // Set mailer to use SendGrid
    // Set the SendGrid API Key, to do it securely check:
    // https://github.com/sendgrid/sendgrid-php/blob/master/README.md
    $mail->SendGridApiKey = '';                           // SendGrid API Key.

    //Uncomment to save email to file
    // $mail->isFile();
    // $mail->EmailFilePath = '/var/log/email/';

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>.';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients.';

    $mail->send();
    echo 'Message has been sent.';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
```

## License
This software is distributed under the [LGPL 3.0](http://www.gnu.org/licenses/lgpl-3.0.html) license. Please read LICENSE for information on the software availability and distribution.

