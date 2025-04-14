<?php
//  S.H. Programming Challenge 
//  April 2025
//  Andrew Leamy

//
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>H.S. Programming Challenge</title>

    <!-- import jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- link bootstrap and custom css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./css/styles.css">
  </head>

  <body>
    <h1>H.S. Programming Challenge</h1>
    <p style="margin-left: 40px"> created by Andrew Leamy </p>
    <br>
    <br>
    <br>

    <div class="container" style="padding-left: 20px">
      <button type="button" onclick="getClinicianStatus(1)">Fetch Status</button>
      <br> <br>
      <p> The current status of Clinician 1 is: <p>
      <div id="curClinicianStatus"> Not yet fetched. </div>
    </div>

    <!-- import boostrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- ajax queries -->
    <script>
      function getClinicianStatus(clinicianID){
        $.ajax({
          url: "./scripts/getClinicianStatus.php",
          type: "POST",
          data: {
            id: clinicianID
          },
          success: function (data){
            console.log("data recieved!");
            $("#curClinicianStatus").text(data);
          },
          error:function(e){
                //the script that runs the curl call to the api failed.
                console.error("getClinicianStatus.php returned an error.");
                console.error(e);
            }  
        });
      }
    </script>
  </body>
</html>