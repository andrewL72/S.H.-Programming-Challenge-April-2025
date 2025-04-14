<?php
/*
* getClinicianStatus.php
* Andrew Leamy, April 2025
* Script recieves a POST request which updates the status of 
* a specified clinician via passed id.
*/

if (isset($_POST["id"]))
{
    include_once "./isInBounds.php";
    $id = $_POST["id"];

    //fetch geoJSON data for specified clinician via call to S.H. provided API
    $cmd = "curl https://3qbqr98twd.execute-api.us-west-2.amazonaws.com/test/clinicianstatus/" . $id;
    
    $response = exec(escapeshellcmd($cmd));
    echo $response; // NOTE: for dev purposes. remove this before release.

    //update the clinicians file to reflect the new status.
    //note that loading in then re-exporting the whole clinicians
    //file would be horrendesly inefficient if this project wasn't limited
    //to 6 clinicians. A more realistic backend implementation would be
    //to use an sql database.
    $file = fopen("../data/clinicians.csv", "r");
    $clinicians = array();
    while(($nextLine = fgetcsv($file, 0 ,",","\"","\\")) !== false)
    {
        $clinicians[] = $nextLine;
    }
    fclose($file);

    $inBounds = isInBounds($response);

    if ($inBounds == 1)
    {
        //clinician is IN BOUNDS
        echo "<p style='color:green'> IN BOUNDS </p>";
        $clinicians[$id][2] = "IN BOUNDS";
    }
    else if ($inBounds == 0)
    {
        //clinician is OUT OF BOUNDS
        echo "<p style='color:red'> OUT OF BOUNDS </p>";
        $clinicians[$id][2] = "OUT OF BOUNDS";
    }
    else if ($inBounds == -1)
    {
        //isInBounds failed. Mostly likely the aws server returned a 400 message which 
        //the function couldn't process.
        echo "<p style='color:black'> SERVER ERROR </p>";
        $clinicians[$id][2] = "ERROR";
    }

    //output clinician data to csv here
    $clinicians[$id][3] = time();
    $clinicians[$id][4] = "json updated lol";

    foreach ($clinicians as $c)
    {
        echo "<br>" . implode(",", $c) . "<br>";
    }
}
else
{
    echo "INVALID POST REQUEST";
}
    


?>