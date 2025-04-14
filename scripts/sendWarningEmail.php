<?php
/*
* sendEmail.php
* Andrew Leamy, April 2025
* defines a function which sends a warning email to
* the server stating that a given clinician has left
* their boundry zone.
*/

//first use Composer to load the phpmailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "../vendor/autoload.php";

//accepts an array representing a row of clinicians.csv
//sends an email warning that the relevant clinician has
//left the boundry area
function sendWarningEmail ($clinicianData)
{
    //create phpmailer object
    $mail = new PHPMailer();

    //authorize email credentials from env data
    $mail->IsSMTP();
    $mail->Host = "smtp.example.com";

    // optional
    // used only when SMTP requires authentication  
    $mail->SMTPAuth = true;
    $mail->Username = 'smtp_username';
    $mail->Password = 'smtp_password';

    //destination email is hard-codded and provided in the challenge instructions.
    $mail->addAddress("sprinter-eng-test@guerrillamail.info");

    //define email headers.

    $subject = "Warning: Clinician " . $clinicianData[1] . " has left their designated safety zone.";
    $mail->Subject = $subject;

    $from = "From: no-reply@sh-warnings.com" . "\r\n";
    $mail->setFrom("no-reply@sh-warnings.com");

    $text = "Warning! Clinician " . $clinicianData[1] . " with ID: " . $clinicianData[0] . " has left their designated safety zone!\n";
    $text .= "They were last seen at the coordinates " . $clinicianData[4] . " on [x].";

    $mail->msgHTML($text);

    if (!$mail->send()) {
        return 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        return 'Message sent!';
    }
}

?>