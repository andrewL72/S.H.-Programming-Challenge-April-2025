<?php
/*
* sendEmail.php
* Andrew Leamy, April 2025
* defines a function which sends a warning email to
* the server stating that a given clinician has left
* their boundry zone.
*/

//first use Composer to load the phpmailer and PHPMailerSendGrid libraries.
use PHPMailer\PHPMailer\PHPMailerSendGrid;
use PHPMailer\PHPMailer\Exception;
require_once '../vendor/autoload.php';

//accepts an array representing a row of clinicians.csv
//sends an email warning that the relevant clinician has
//left the boundry area
function sendWarningEmail ($clinicianData)
{
    //email is sent via the service SendGrid, which is accessed through
    //the PHPMailerSendGrid library
    $mail = new PHPMailerSendGrid();

    try {
        //initiate sendGrid settings
        $mail->isSendGrid();

        //note: in a professional build, this should be hidden in an env variable.
        //$mail->SendGridApiKey = "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"; 
        $mail->SendGridApiKey = "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"; 
    
        //begin setting email metadata
        $mail->setFrom('noreply@sh-warnings.com', 'noreply');

        //hardcoded email provided by project instructions
        $mail->addAddress('sprinter-eng-test@guerrillamail.info');
    
        $subject = "Warning: Clinician " . $clinicianData[1] . " has left their designated safety zone.";
        $mail->Subject = $subject;

        $mail->isHTML(true);
        $text = "Warning! Clinician " . $clinicianData[1] . " with ID: " . $clinicianData[0] . " has left their designated safety zone!\n";
        $text .= "They were last seen at the coordinates " . $clinicianData[4];
        $mail->Body = $text;
    
        $mail->send();
        return 'Message has been sent.';
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

?>