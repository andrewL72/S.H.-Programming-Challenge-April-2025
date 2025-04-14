<?php
/*
* sendEmail.php
* Andrew Leamy, April 2025
* defines a function which sends a warning email to
* the server stating that a given clinician has left
* their boundry zone.
*/

//sends a warning email stating that the clinician of the 
//passed id has left their boundry zone.
function($id)
{
    //destination email is hard-codded and provided in the challenge instructions.
    $to = "sprinter-eng-test@guerrillamail.info";

    //load in clinician data.
    $cFile = fopen("../data/clinicians.csv", "r");
    $clinicians = array();
    while(($nextLine = fgetcsv($cFile, 0 ,",","\"","\\")) !== false)
    {
        $clinicians[] = $nextLine;
    }
    fclose($cFile);

    $target = $clinicians[$id];

    //define email headers.

    $subject = "Warning: Clinician " . $target[1] . " has left their designated safety zone.";

    $from = "From: no-reply@sh-warnings.com" . "\r\n";

    $text = "Warning! Clinician " . $target[1] . " with ID: " . $target[0] . " has left their designated safety zone!";

    mail($to, $subject, $text, $from);
}

?>