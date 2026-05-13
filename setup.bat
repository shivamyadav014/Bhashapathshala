@echo off
REM Language Learning Platform - Quick Setup Script for Windows

echo.
echo ================================
echo Laravel Language Learning Platform
echo Quick Setup
echo ================================
echo.

echo [1/5] Installing Composer Dependencies...
call composer install
if errorlevel 1 (
    echo Error: Failed to install composer dependencies
    echo Make sure Composer is installed: https://getcomposer.org
    pause
    exit /b 1
)

echo.
echo [2/5] Copying Environment File...
if not exist .env (
    copy .env.example .env
    echo .env file created
) else (
    echo .env file already exists
)

echo.
echo [3/5] Generating Application Key...
call php artisan key:generate

echo.
echo [4/5] Running Database Migrations...
echo Please ensure MySQL is running and database credentials in .env are correct
call php artisan migrate

echo.
echo [5/5] Seeding Database with Sample Data...
call php artisan db:seed

echo.
echo ================================
echo Setup Complete!
echo ================================
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo Then access the API at:
echo   http://localhost:8000/api
echo.
echo Test Credentials:
echo   Admin: admin@languageapp.com / password123
echo   Instructor: maria@languageapp.com / password123
echo   Student: john@example.com / password123
echo.
pause
