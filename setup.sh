#!/bin/bash
# Language Learning Platform - Quick Setup Script for Linux/Mac

echo ""
echo "================================"
echo "Laravel Language Learning Platform"
echo "Quick Setup"
echo "================================"
echo ""

echo "[1/5] Installing Composer Dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "Error: Failed to install composer dependencies"
    echo "Make sure Composer is installed: https://getcomposer.org"
    exit 1
fi

echo ""
echo "[2/5] Copying Environment File..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created"
else
    echo ".env file already exists"
fi

echo ""
echo "[3/5] Generating Application Key..."
php artisan key:generate

echo ""
echo "[4/5] Running Database Migrations..."
echo "Please ensure MySQL is running and database credentials in .env are correct"
php artisan migrate

echo ""
echo "[5/5] Seeding Database with Sample Data..."
php artisan db:seed

echo ""
echo "================================"
echo "Setup Complete!"
echo "================================"
echo ""
echo "To start the development server, run:"
echo "  php artisan serve"
echo ""
echo "Then access the API at:"
echo "  http://localhost:8000/api"
echo ""
echo "Test Credentials:"
echo "  Admin: admin@languageapp.com / password123"
echo "  Instructor: maria@languageapp.com / password123"
echo "  Student: john@example.com / password123"
echo ""
