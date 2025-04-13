<?php
    $status = exec(escapeshellcmd("curl https://3qbqr98twd.execute-api.us-west-2.amazonaws.com/test/clinicianstatus/1"));

    echo $status;
?>