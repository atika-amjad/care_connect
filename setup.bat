@echo off
echo Care Connect - Setup Script
echo ===========================

where php >nul 2>nul || (echo PHP not found. Install PHP 8.2+ && exit /b 1)
where composer >nul 2>nul || (echo Composer not found. Install Composer && exit /b 1)

echo Installing PHP dependencies...
call composer install --no-dev --no-interaction --no-scripts
if errorlevel 1 (
    echo.
    echo Composer install failed in this folder. Trying outside OneDrive...
    if not exist C:\temp\care_connect_install mkdir C:\temp\care_connect_install
    copy /Y composer.json C:\temp\care_connect_install\
    copy /Y composer.lock C:\temp\care_connect_install\
    cd /d C:\temp\care_connect_install
    call composer install --no-dev --no-interaction --no-scripts
    if errorlevel 1 exit /b 1
    cd /d %~dp0
    if exist vendor rmdir /s /q vendor
    robocopy C:\temp\care_connect_install\vendor vendor /E /NFL /NDL /NJH /NJS /nc /ns /np >nul
    call composer dump-autoload --no-scripts --no-interaction
)

if not exist .env copy .env.example .env
if not exist database\database.sqlite type nul > database\database.sqlite

php artisan key:generate --force
php artisan migrate --force --seed

where npm >nul 2>nul && (
    echo Building frontend assets...
    call npm install
    call npm run build
)

echo.
echo Setup complete! Run: php artisan serve
echo Then open http://127.0.0.1:8000
