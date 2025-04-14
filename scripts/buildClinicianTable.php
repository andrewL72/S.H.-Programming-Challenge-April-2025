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
$output = "<table> <tr> <th></th> <th>Clinician</th> <th>Coordinates</th> <th>Status</th> <th hidden>Warning Message</th> </tr>";

foreach ($clinicians as $c)
{
    $output .= "<tr>";
    $output .= "<td> <input type='radio' id='radio" . $c[0] . "' value='" . $c[0] . "'> </td>";
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

    if ($c[2] == "OUT OF BOUNDS")
    {
        $output .= "<td> <span style='color:red'> ! Warning email sent on [x] </span> </td>";
    }

    $output .= "</tr>";
}

$output .= "</table>";

echo $output;
?>