copy ".\data\clinicians default.csv" ".\data\clinicians.csv"
copy ".\data\event_log default.csv" ".\data\event_log.csv"
cd ".\scripts"
start clinicianTrackingService.bat
cd ".."
start "" http://localhost:8000/index.php
php -S localhost:8000
