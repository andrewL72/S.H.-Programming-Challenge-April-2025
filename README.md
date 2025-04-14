# S.H.-Programming-Challenge-April-2025

Programming challenge set forth by S.H. in April of 2025,
completed by Andrew Leamy

Requires PHP 8 and Composer to be installed on your device to 
run correctly.

To begin running the service, simply run 'runServer.bat'. This will
both begin the clinician tracking service, which polls once a minute,
and also boot up a localhost server on port 8000 which displays a 
webpage displaying clinician tracking data.

Unfortunately, email functionality is not working due to repeated errors
with using the API of the email domain service I tried to implement, SendGrid.