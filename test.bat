@echo off

php artisan migrate:fresh --seed

vendor/bin/phpunit tests/

php artisan migrate:fresh --seed

set /P id="The batch file is complete:"

pause
exit