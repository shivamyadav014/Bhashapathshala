# BhashaPathshala

An online learning platform for language education featuring courses, exercises, quizzes, progress tracking, and performance feedback.

## Features

- **Courses Management**: Create, edit, and manage language courses
- **Exercises**: Interactive exercises for language practice
- **Quizzes**: Assessment tools to test understanding
- **Progress Tracking**: Monitor user learning progress
- **Performance Feedback**: Detailed feedback on user performance
- **User Management**: Authentication and user profiles
- **Admin Dashboard**: Manage platform content

## Project Structure

```
app/
├── Models/              # Database models
├── Http/
│   ├── Controllers/    # Application controllers
│   └── Requests/       # Form request validations
database/
├── migrations/         # Database migrations
└── seeders/           # Database seeders
routes/                # API and web routes
resources/views/       # View templates
config/                # Configuration files
```

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Run migrations: `php artisan migrate`
5. Seed database: `php artisan db:seed`
6. Start the server: `php artisan serve`

## Database Schema

## User Roles

### Admin Role
- Full access to all platform features and settings
- Manage users (create, edit, delete, assign roles)
- Manage all courses, lessons, quizzes, and exercises
- View and manage analytics, reports, and system settings
- Assign or revoke instructor/admin roles
- Moderate user-generated content and handle support tickets

### Instructor Role
- Create, edit, and delete their own courses, lessons, quizzes, and exercises
- View analytics and reports for their own courses and students
- Manage enrollments for their courses
- Interact with students (answer questions, provide feedback)
- Cannot manage other instructors or admins
- Cannot access system-wide settings or user management

### Implementation
- The `users` table has a `role` field: `student`, `instructor`, or `admin`.
- Role-based middleware protects routes for each role.
- The `User` model provides `isAdmin()` and `isInstructor()` helpers.
- See `routes/web.php` for route protection examples.

### Users Table
- id, name, email, password, role, created_at, updated_at

### Courses Table
- id, title, description, language, level, instructor_id, created_at, updated_at

### Lessons Table
- id, course_id, title, content, order, created_at, updated_at

### Exercises Table
- id, lesson_id, title, description, exercise_type, content, created_at, updated_at

### Quizzes Table
- id, course_id, title, description, passing_score, created_at, updated_at

### Quiz Questions Table
- id, quiz_id, question, question_type, options, correct_answer, points, created_at, updated_at

### User Progress Table
- id, user_id, course_id, completion_percentage, last_accessed, created_at, updated_at

### User Quiz Results Table
- id, user_id, quiz_id, score, passed, completed_at, created_at, updated_at

### Exercise Submissions Table
- id, user_id, exercise_id, submission_content, score, feedback, submitted_at, created_at, updated_at

## API Endpoints

### Authentication
- POST /api/auth/register
- POST /api/auth/login
- POST /api/auth/logout

### Courses
- GET /api/courses
- GET /api/courses/{id}
- POST /api/courses (admin)
- PUT /api/courses/{id} (admin)
- DELETE /api/courses/{id} (admin)

### Lessons
- GET /api/courses/{course_id}/lessons
- GET /api/lessons/{id}

### Exercises
- GET /api/lessons/{lesson_id}/exercises
- POST /api/exercises/{id}/submit

### Quizzes
- GET /api/courses/{course_id}/quizzes
- POST /api/quizzes/{id}/submit

### Progress
- GET /api/users/{user_id}/progress
- GET /api/users/{user_id}/quiz-results

## Technologies Used

- Laravel 11
- MySQL
- PHP 8.1+
- Sanctum for API Authentication

## License

MIT License
