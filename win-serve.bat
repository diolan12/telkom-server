@echo off

php -S localhost:8080 -t public

set /P id="The batch file is complete:"

pause
exit