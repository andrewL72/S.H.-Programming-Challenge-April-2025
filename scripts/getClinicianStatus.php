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

    //fetch geoJSON data for specified clinician via call to S.H. provided API
    $cmd = "curl https://3qbqr98twd.execute-api.us-west-2.amazonaws.com/test/clinicianstatus/" . $_POST["id"];
    //$id = $_POST["id"];
    $response = exec(escapeshellcmd($cmd));

    $inBounds = isInBounds($response);

    echo $inBounds;
}
else
{
    echo "INVALID POST REQUEST";
}
    


?>