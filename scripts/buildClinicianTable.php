<?php
/*
* buildClinicianTable.php
* Andrew Leamy, April 2025
* reads data off of clinicians.csv and returns HTML for a 
* table to display this data to the user.
*/

//first, load in clinician data
$cFile = fopen("../data/clinicians.csv", "r");
$clinicians = array();
while(($nextLine = fgetcsv($cFile, 0 ,",","\"","\\")) !== false)
{
    $clinicians[] = $nextLine;
}
fclose($cFile);

//remove column headers row
unset($clinicians[0]);

//build table header.
$output = "<table> <tr> <th>Clinician</th> <th>Coordinates</th> <th>Status</th>"

?>