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
//unset($clinicians[0]);

//build table header.
$output = "<table class='table table-striped'> <thead class='thead-light'> <tr> <th scope='col'></th> <th scope='col'>Clinician</th> 
            <th scope='col'>Coordinates</th> <th scope='col'>Status</th> <th scope='col'>GeoJSON</th> </tr> </thead>";

foreach ($clinicians as $c)
{
    //skip column headers row
    if($c[0] == "id")
    {
        continue;
    }
    $output .= "<tr>";
    $output .= "<th scope='row'> <input type='radio' id='radio" . $c[0] . "' value='" . $c[0] . "'> </th>";
    $output .= "<td>" . $c[1] . "</td>";
    $output .= "<td>" . $c[4] . "</td>";

    if ($c[2] == "IN BOUNDS")
    {
        $output .= "<td> <span style='color:green'>" . $c[2] . "</span> </td>";
    }
    else if ($c[2] == "OUT OF BOUNDS")
    {
        $output .= "<td> <span style='color:red'>" . $c[2] . "</span> </td>";
    }
    else
    {
        $output .= "<td> <span style='color:black'>" . $c[2] . "</span> </td>";
    }

    //geojson data goes here.
    $output .= "<td> <div class='geoJSONBox' style='height:40px;width:300px;border:1px solid;overflow:auto;'>" . $c[5] . "</div> </td>";

    $output .= "</tr>";
}

$output .= "</table>";

echo $output;
?>