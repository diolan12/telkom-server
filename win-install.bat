@echo off

php artisan migrate:fresh --seed

set /P id="Database configuration is complete:"

pause

exit