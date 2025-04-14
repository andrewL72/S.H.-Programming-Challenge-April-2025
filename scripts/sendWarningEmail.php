<?php
/*
* sendEmail.php
* Andrew Leamy, April 2025
* defines a function which sends a warning email to
* the server stating that a given clinician has left
* their boundry zone.
*/

//accepts an array representing a row of clinicians.csv
//sends an email warning that the relevant clinician has
//left the boundry area
function sendWarningEmail ($clinicianData)
{
    //destination email is hard-codded and provided in the challenge instructions.
    $to = "sprinter-eng-test@guerrillamail.info";

    //define email headers.

    $subject = "Warning: Clinician " . $clinicianData[1] . " has left their designated safety zone.";

    $from = "From: no-reply@sh-warnings.com" . "\r\n";

    $text = "Warning! Clinician " . $clinicianData[1] . " with ID: " . $clinicianData[0] . " has left their designated safety zone!\n";
    $text .= "They were last seen at the coordinates " . $clinicianData[4] . " on [x].";

    return mail($to, $subject, $text, $from);
}

?>