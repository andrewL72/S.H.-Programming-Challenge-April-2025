<?php
/*
* getAllCliniciansStatus.php
* Andrew Leamy, April 2025
* Script updates all clinicians specified in clinicians.csv with
* their current location status. Also updates event_log.csv for
* each clinician.
* if any clinicians are detected as newly out of bounds, 
* this script will also call sendWarningEmail if a clinician is
* newly detected as out of bounds.
*/

include_once "./isInBounds.php";

//first, load in clinician data
$cFile = fopen("../data/clinicians.csv", "r");
$clinicians = array();
while(($nextLine = fgetcsv($cFile, 0 ,",","\"","\\")) !== false)
{
    $clinicians[] = $nextLine;
}
fclose($cFile);

//update csv file
$cFile = fopen("../data/clinicians.csv", "w");
$eFile = fopen("../data/event_log.csv", "a");
foreach ($clinicians as $c)
{
    $id = $c[0];
    
    //skip column name row
    if ($id == "id")
    {
        continue;
    }

    $cmd = "curl https://3qbqr98twd.execute-api.us-west-2.amazonaws.com/test/clinicianstatus/" . $id;
    $response = exec(escapeshellcmd($cmd));
    $geoJSON = json_decode($response);
    $inBounds = isInBounds($geoJSON);

    //update event log here

    //first update clinician status
    if ($inBounds == 1)
    {
        //clinician is IN BOUNDS
        //echo "<p style='color:green'> IN BOUNDS </p>";
        $c[2] = "IN BOUNDS";
    }
    else if ($inBounds == 0)
    {
        //clinician is OUT OF BOUNDS
        //echo "<p style='color:red'> OUT OF BOUNDS </p>";
        $c[2] = "OUT OF BOUNDS";
    }
    else if ($inBounds == -1)
    {
        //isInBounds failed. Mostly likely the aws server returned a 400 message which 
        //the function couldn't process.
        //echo "<p style='color:black'> SERVER ERROR </p>";
        $c[2] = "ERROR";
    }

    //then update timestamp
    $c[3] = time();

    //update coordinates
    $c[4] = "[" . implode(",", $geoJSON->features[0]->geometry->coordinates) . "]";

    //update geoJSON
    $c[5] = "updated lol";

    echo "<br>" . implode(",", $c) . "<br>";
    fputcsv($cFile, $c, ",", "\"", "\\", "\n");

    $event = array($id, $c[2], $c[3], $c[4], "updated lol");
    fputcsv($eFile, $event, ",", "\"", "\\", "\n");

}
fclose($cFile);
fclose($eFile);
?>