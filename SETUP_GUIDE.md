# Language Learning Platform - Installation & Setup Guide

## Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Node.js and npm (for front-end build tools)

## Installation Steps

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies (if you plan to use Vite for frontend)
npm install
```

### 2. Environment Setup

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Configuration

Edit the `.env` file and update the database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=language_learning_platform
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Database Migration

```bash
# Run migrations to create tables
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 5. Start the Development Server

```bash
# Start Laravel development server
php artisan serve

# In another terminal, if using npm build tools
npm run dev
```

The application will be available at `http://localhost:8000`

## API Testing

You can test the API using tools like:
- Postman
- Insomnia
- Thunder Client
- cURL

### Test Credentials

#### Admin User
- Email: `admin@languageapp.com`
- Password: `password123`

#### Instructor Users
- Email: `maria@languageapp.com` (Spanish instructor)
- Password: `password123`
- Email: `pierre@languageapp.com` (French instructor)
- Password: `password123`

#### Student Users
- Email: `john@example.com`
- Password: `password123`
- Email: `emma@example.com`
- Password: `password123`

## API Endpoints Overview

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout (requires auth)
- `GET /api/auth/profile` - Get user profile (requires auth)
- `PUT /api/auth/profile` - Update user profile (requires auth)

### Courses
- `GET /api/courses` - List all published courses
- `GET /api/courses/{id}` - Get course details
- `POST /api/courses` - Create course (instructor only)
- `PUT /api/courses/{id}` - Update course (instructor only)
- `DELETE /api/courses/{id}` - Delete course (instructor only)
- `POST /api/courses/{id}/enroll` - Enroll in course
- `GET /api/my-enrollments` - Get user enrollments
- `GET /api/courses/{id}/progress` - Get course progress

### Lessons
- `GET /api/courses/{courseId}/lessons` - Get course lessons
- `GET /api/lessons/{id}` - Get lesson details
- `POST /api/lessons` - Create lesson (instructor only)
- `PUT /api/lessons/{id}` - Update lesson
- `POST /api/lessons/{id}/complete` - Mark lesson complete
- `GET /api/lessons/{id}/progress` - Get lesson progress
- `PUT /api/lessons/{id}/progress` - Update lesson progress

### Exercises
- `GET /api/lessons/{lessonId}/exercises` - Get lesson exercises
- `GET /api/exercises/{id}` - Get exercise details
- `POST /api/exercises` - Create exercise (instructor only)
- `POST /api/exercises/{id}/submit` - Submit exercise
- `GET /api/exercises/{id}/submission` - Get user submission
- `GET /api/exercises/{exerciseId}/submissions` - Get all submissions (instructor)
- `PUT /api/submissions/{submissionId}/grade` - Grade submission (instructor)
- `GET /api/exercises/{exerciseId}/stats` - Get exercise statistics

### Quizzes
- `GET /api/courses/{courseId}/quizzes` - Get course quizzes
- `GET /api/quizzes/{id}` - Get quiz details
- `POST /api/quizzes` - Create quiz (instructor only)
- `POST /api/quizzes/{quizId}/questions` - Add quiz question
- `POST /api/quizzes/{id}/submit` - Submit quiz
- `GET /api/quizzes/{id}/results` - Get quiz results
- `GET /api/quizzes/{quizId}/stats` - Get quiz statistics

### Progress & Analytics
- `GET /api/dashboard` - Get user dashboard
- `GET /api/courses/{courseId}/progress-details` - Get detailed course progress
- `GET /api/lessons/{lessonId}/progress-details` - Get detailed lesson progress
- `GET /api/quizzes/{quizId}/progress` - Get quiz progress
- `GET /api/performance-report` - Get comprehensive performance report

## Features

### For Students
- Browse and enroll in language courses
- View lessons and course materials
- Complete exercises and submit solutions
- Take quizzes and track performance
- View progress and performance analytics
- Get feedback on submissions

### For Instructors
- Create and manage courses
- Create lessons with multimedia content
- Design exercises and quizzes
- Grade student submissions
- View student progress and performance
- Analyze course and quiz statistics

### For Administrators
- Manage all users (students, instructors)
- Moderate content
- View platform analytics
- Manage system settings

## Database Schema

### Users
Stores user information including role (student/instructor/admin)

### Courses
Language courses with metadata and publish status

### Lessons
Course lessons with content and progression

### Exercises
Practice exercises with different types (listening, speaking, reading, etc.)

### Quizzes
Assessment quizzes with questions and scoring

### CourseEnrollments
Track student enrollments and progress

### ExerciseSubmissions
Student exercise submissions with grades and feedback

### UserQuizResults
Student quiz results with scores and pass/fail status

### LessonProgress
Track individual lesson completion and progress

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running
- Check credentials in `.env` file
- Verify database exists

### Migration Errors
```bash
# Reset database (careful - deletes all data)
php artisan migrate:reset

# Re-run migrations
php artisan migrate
```

### Composer Issues
```bash
# Update composer
composer update

# Clear cache
composer clear-cache
```

## Performance Tips

1. Enable query caching for frequently accessed data
2. Use pagination for large datasets
3. Implement rate limiting for API endpoints
4. Use database indexes on frequently queried fields

## Security Recommendations

1. Change APP_KEY before production
2. Use strong database passwords
3. Keep dependencies updated
4. Enable HTTPS in production
5. Implement proper CORS policies
6. Use rate limiting
7. Validate all user inputs

## Contributing

When contributing to the project:
1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation
4. Create feature branches

## Support

For issues or questions, please create an issue in the repository or contact the development team.

## License

This project is licensed under the MIT License.
