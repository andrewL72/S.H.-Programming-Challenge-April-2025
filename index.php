<?php
//  S.H. Programming Challenge 
//  April 2025
//  Andrew Leamy

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
    <h2> created by Andrew Leamy </h2>
    <br>
    <br>

    <div class="container" style="padding-left: 20px">
      <p>The current status of all Clinicians are:<p>
      <div id="clinicianTable">
        <table class='table table-striped'> <thead class='thead-light'> 
          <tr> <th scope='col'></th> <th scope='col'>Clinician</th> <th scope='col'>Coordinates</th> <th scope='col'>Status</th> <th scope='col'>GeoJSON</th> </tr> </thead>
          <tr> <td><input type='radio'></td> <td></td> <td></td> <td></td> <td></td> </tr>
          <tr> <td><input type='radio'></td> <td></td> <td></td> <td></td> <td></td> </tr>
          <tr> <td><input type='radio'></td> <td></td> <td></td> <td></td> <td></td> </tr>
          <tr> <td><input type='radio'></td> <td></td> <td></td> <td></td> <td></td> </tr>
          <tr> <td><input type='radio'></td> <td></td> <td></td> <td></td> <td></td> </tr>
          <tr> <td><input type='radio'></td> <td></td> <td></td> <td></td> <td></td> </tr>
        </table>
      </div>
      <br>
      <p>Clinician locations are polled every 60 seconds.</p>
      <p>This table automatically refreshes every 60 seconds.</p>
      <br>
      <div><button onclick='manualTableUpdate()'>Update Manually</button></div>
    </div>

    <div id="loadingOverlay">
      <div class="cv-spinner">
        <span class="spinner"></span>
      </div>
    </div>


    <!-- import boostrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- ajax queries -->
    <script>
      var updateTimeout;

      function getClinicianStatus(clinicianID){
        $.ajax({
          url: "./scripts/getAllCliniciansStatus.php",
          type: "POST",
          data: {
            id: clinicianID
          },
          success: function (data){
            console.log("data recieved from getAllCliniciansStatus!");
            $("#clinicianStatus").html(data);
          },
          error:function(e){
                //the script that runs the curl call to the api failed.
                console.error("getAllCliniciansStatus.php returned an error.");
                console.error(e);
            }  
        });
      }

      function updateCliniciansTable()
      {
        $.ajax({
          url: "./scripts/buildCliniciansTable.php",
          type: "GET",
          beforeSend: function() {
            $("#loadingOverlay").fadeIn(300);
          },
          success: function (data){
            console.log("data recieved from buildCliniciansTable!");
            $("#clinicianTable").html(data);
          },
          error:function(e){
                //the script that runs the curl call to the api failed.
                console.error("buildCliniciansTable.php returned an error.");
                console.error(e);
            },
          complete: function(){
            $("#loadingOverlay").fadeOut(300);
            updateTimeout = setTimeout(updateCliniciansTable, 65000);
          }  
        });
      }

      function manualTableUpdate()
      {
        clearTimeout(updateTimeout);
        updateCliniciansTable();
      }

      $(document).ready(function(){
        $("#loadingOverlay").fadeIn(300);
        setTimeout(updateCliniciansTable, 5000);
      });
    </script>
  </body>
</html>