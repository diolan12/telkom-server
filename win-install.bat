@echo off

php artisan migrate:fresh --seed

echo "Database configuration is complete:"

pause

exit