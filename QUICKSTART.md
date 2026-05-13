# Quick Start Guide - Language Learning Platform

## Prerequisites Check

Before running the project, ensure you have:

✓ PHP 8.1 or higher
  - Check: `php --version`

✓ MySQL 8.0 or higher (running)
  - Check: `mysql --version`
  - Ensure MySQL service is started

✓ Composer
  - Check: `composer --version`
  - Download from: https://getcomposer.org

✓ Git (optional, for version control)
  - Check: `git --version`

## Step 1: Navigate to Project Directory

```bash
cd "c:\Users\shiva\OneDrive\Desktop\laravel prfoject"
```

## Step 2: Run Automatic Setup (Windows)

```bash
setup.bat
```

Or run these commands manually:

```bash
# Install dependencies
composer install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Create database in MySQL first!
# mysql> CREATE DATABASE language_learning_platform;

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

## Step 3: Update .env if Needed

Edit `.env` file with your database credentials:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=language_learning_platform
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

## Step 4: Start Development Server

```bash
php artisan serve
```

The server will start at: `http://localhost:8000`

## Step 5: Test the API

### Login as Student
```bash
POST http://localhost:8000/api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

### View Courses
```bash
GET http://localhost:8000/api/courses
```

### View Dashboard
```bash
GET http://localhost:8000/api/dashboard
Authorization: Bearer YOUR_TOKEN_FROM_LOGIN
```

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@languageapp.com | password123 |
| Instructor (Spanish) | maria@languageapp.com | password123 |
| Instructor (French) | pierre@languageapp.com | password123 |
| Student 1 | john@example.com | password123 |
| Student 2 | emma@example.com | password123 |

## API Documentation

See `SETUP_GUIDE.md` for complete API endpoint documentation and examples.

## Troubleshooting

### MySQL Connection Error
```bash
# Make sure MySQL is running
# Windows: Services > MySQL80 > Start
# Mac: brew services start mysql
# Linux: sudo systemctl start mysql
```

### Composer Issues
```bash
# Clear cache and reinstall
composer clear-cache
composer install
```

### Database Migration Error
```bash
# Reset database (WARNING: DELETES ALL DATA)
php artisan migrate:reset

# Re-run migrations
php artisan migrate
php artisan db:seed
```

### Port 8000 Already in Use
```bash
# Use different port
php artisan serve --port=8001
```

## Database Management

### View Database
```bash
mysql -u root -p language_learning_platform

# List tables
SHOW TABLES;

# View users
SELECT * FROM users;
```

### Reset Everything
```bash
# Back in Laravel terminal, stop the server (Ctrl+C)

# Reset database
php artisan migrate:reset

# Re-run everything
php artisan migrate
php artisan db:seed
```

## Next Steps

1. **Frontend Development**: Build a Vue.js/React frontend
2. **Additional Features**: Implement notifications, messaging, advanced analytics
3. **Testing**: Write unit and feature tests
4. **Deployment**: Deploy to production server

## Useful Commands

```bash
# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName

# Create new controller
php artisan make:controller ControllerName

# Create new seeder
php artisan make:seeder SeederName

# Run tinker (interactive shell)
php artisan tinker

# List all routes
php artisan route:list

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Project Structure

```
laravel-project/
├── app/
│   ├── Models/              # Database models
│   ├── Http/
│   │   ├── Controllers/     # API controllers
│   │   ├── Requests/        # Form validations
│   │   └── Middleware/      # HTTP middleware
│   └── Policies/            # Authorization policies
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Sample data
├── routes/
│   └── api.php              # API routes
├── config/                  # Configuration
├── storage/                 # File storage
└── vendor/                  # Dependencies
```

## Support Resources

- Laravel Documentation: https://laravel.com/docs
- API Design Best Practices: https://restfulapi.net
- MySQL Documentation: https://dev.mysql.com/doc/
- Postman for API Testing: https://www.postman.com

## Success Indicators

✓ No errors during setup
✓ Can access http://localhost:8000/api/courses
✓ Can login with test credentials
✓ Database has sample data
✓ Can see dashboard stats

Happy coding! 🚀
