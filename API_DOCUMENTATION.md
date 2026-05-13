# Language Learning Platform - API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### Register a New User
**POST** `/auth/register`

Request body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "student"
}
```

Response (201):
```json
{
  "message": "User registered successfully",
  "user": { ... },
  "token": "token_string"
}
```

---

### Login
**POST** `/auth/login`

Request body:
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

Response (200):
```json
{
  "message": "Login successful",
  "user": { ... },
  "token": "token_string"
}
```

---

### Logout (Protected)
**POST** `/auth/logout`

Response (200):
```json
{
  "message": "Logged out successfully"
}
```

---

### Get User Profile (Protected)
**GET** `/auth/profile`

Response (200):
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "role": "student",
  "bio": "Language learner",
  "profile_image": null,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

### Update User Profile (Protected)
**PUT** `/auth/profile`

Request body:
```json
{
  "name": "John Updated",
  "bio": "Advanced language learner",
  "profile_image": "https://example.com/image.jpg"
}
```

Response (200):
```json
{
  "message": "Profile updated successfully",
  "user": { ... }
}
```

---

## Course Endpoints

### List All Courses (Public)
**GET** `/courses`

Query parameters:
- `page`: Page number (default: 1)

Response (200):
```json
{
  "data": [
    {
      "id": 1,
      "title": "Spanish for Beginners",
      "description": "Learn Spanish basics...",
      "language": "Spanish",
      "level": "beginner",
      "instructor_id": 2,
      "rating": 4.5,
      "is_published": true,
      "instructor": { ... }
    }
  ],
  "pagination": { ... }
}
```

---

### Get Course Details (Public)
**GET** `/courses/{id}`

Response (200):
```json
{
  "id": 1,
  "title": "Spanish for Beginners",
  "description": "Learn Spanish basics...",
  "language": "Spanish",
  "level": "beginner",
  "duration_hours": 20,
  "rating": 4.5,
  "instructor": { ... },
  "lessons": [ ... ],
  "quizzes": [ ... ]
}
```

---

### Create Course (Protected - Instructor Only)
**POST** `/courses`

Request body:
```json
{
  "title": "Italian for Beginners",
  "description": "Learn Italian basics",
  "language": "Italian",
  "level": "beginner",
  "thumbnail": "https://example.com/thumbnail.jpg",
  "duration_hours": 25
}
```

Response (201):
```json
{
  "message": "Course created successfully",
  "course": { ... }
}
```

---

### Update Course (Protected - Course Owner)
**PUT** `/courses/{id}`

Request body:
```json
{
  "title": "Spanish for Beginners - Updated",
  "description": "Updated description",
  "is_published": true
}
```

Response (200):
```json
{
  "message": "Course updated successfully",
  "course": { ... }
}
```

---

### Delete Course (Protected - Course Owner)
**DELETE** `/courses/{id}`

Response (200):
```json
{
  "message": "Course deleted successfully"
}
```

---

### Enroll in Course (Protected)
**POST** `/courses/{id}/enroll`

Response (201):
```json
{
  "message": "Enrolled in course successfully",
  "enrollment": {
    "id": 1,
    "user_id": 3,
    "course_id": 1,
    "status": "enrolled",
    "completion_percentage": 0,
    "enrolled_at": "2024-01-15T10:00:00.000000Z"
  }
}
```

---

### Get My Enrollments (Protected)
**GET** `/my-enrollments`

Query parameters:
- `page`: Page number (default: 1)

Response (200):
```json
{
  "data": [ ... ],
  "pagination": { ... }
}
```

---

### Get Course Progress (Protected)
**GET** `/courses/{id}/progress`

Response (200):
```json
{
  "id": 1,
  "user_id": 3,
  "course_id": 1,
  "status": "in_progress",
  "completion_percentage": 45,
  "enrolled_at": "2024-01-15T10:00:00.000000Z"
}
```

---

## Lesson Endpoints

### Get Course Lessons (Public)
**GET** `/courses/{courseId}/lessons`

Response (200):
```json
[
  {
    "id": 1,
    "course_id": 1,
    "title": "Greetings and Introductions",
    "content": "Learn how to greet people...",
    "order": 1,
    "duration_minutes": 30,
    "video_url": "https://example.com/video1",
    "is_published": true
  }
]
```

---

### Get Lesson Details (Public)
**GET** `/lessons/{id}`

Response (200):
```json
{
  "id": 1,
  "course_id": 1,
  "title": "Greetings and Introductions",
  "content": "Learn how to greet people...",
  "notes": "Important phrases...",
  "order": 1,
  "duration_minutes": 30,
  "video_url": "https://example.com/video1",
  "is_published": true,
  "exercises": [ ... ]
}
```

---

### Create Lesson (Protected - Instructor Only)
**POST** `/lessons`

Request body:
```json
{
  "course_id": 1,
  "title": "New Lesson",
  "content": "Lesson content...",
  "notes": "Additional notes",
  "order": 4,
  "duration_minutes": 45,
  "video_url": "https://example.com/video"
}
```

Response (201):
```json
{
  "message": "Lesson created successfully",
  "lesson": { ... }
}
```

---

### Update Lesson (Protected - Course Owner)
**PUT** `/lessons/{id}`

Request body:
```json
{
  "title": "Updated Lesson Title",
  "is_published": true
}
```

Response (200):
```json
{
  "message": "Lesson updated successfully",
  "lesson": { ... }
}
```

---

### Mark Lesson as Completed (Protected)
**POST** `/lessons/{id}/complete`

Response (200):
```json
{
  "message": "Lesson marked as completed",
  "progress": { ... }
}
```

---

### Get Lesson Progress (Protected)
**GET** `/lessons/{id}/progress`

Response (200):
```json
{
  "id": 1,
  "user_id": 3,
  "lesson_id": 1,
  "is_completed": false,
  "progress_percentage": 60,
  "started_at": "2024-01-15T10:00:00.000000Z"
}
```

---

### Update Lesson Progress (Protected)
**PUT** `/lessons/{id}/progress`

Request body:
```json
{
  "progress_percentage": 80
}
```

Response (200):
```json
{
  "id": 1,
  "user_id": 3,
  "lesson_id": 1,
  "progress_percentage": 80,
  "is_completed": false
}
```

---

## Exercise Endpoints

### Get Lesson Exercises (Public)
**GET** `/lessons/{lessonId}/exercises`

Response (200):
```json
[
  {
    "id": 1,
    "lesson_id": 1,
    "title": "Greetings Matching Exercise",
    "description": "Match Spanish greetings...",
    "exercise_type": "matching",
    "difficulty_level": 1,
    "points": 10
  }
]
```

---

### Get Exercise Details (Public)
**GET** `/exercises/{id}`

Response (200):
```json
{
  "id": 1,
  "lesson_id": 1,
  "title": "Greetings Matching Exercise",
  "description": "Match Spanish greetings...",
  "exercise_type": "matching",
  "content": "{...}",
  "instructions": "Match the items",
  "difficulty_level": 1,
  "points": 10
}
```

---

### Create Exercise (Protected - Instructor Only)
**POST** `/exercises`

Request body:
```json
{
  "lesson_id": 1,
  "title": "New Exercise",
  "description": "Exercise description",
  "exercise_type": "multiple_choice",
  "content": "Exercise content",
  "instructions": "Instructions for exercise",
  "difficulty_level": 2,
  "points": 15
}
```

Response (201):
```json
{
  "message": "Exercise created successfully",
  "exercise": { ... }
}
```

---

### Submit Exercise (Protected)
**POST** `/exercises/{id}/submit`

Request body:
```json
{
  "submission_content": "User's answer or solution"
}
```

Response (201):
```json
{
  "message": "Exercise submitted successfully",
  "submission": {
    "id": 1,
    "user_id": 3,
    "exercise_id": 1,
    "submission_content": "User's answer...",
    "status": "submitted",
    "submitted_at": "2024-01-15T10:00:00.000000Z"
  }
}
```

---

### Get User's Exercise Submission (Protected)
**GET** `/exercises/{id}/submission`

Response (200):
```json
{
  "id": 1,
  "user_id": 3,
  "exercise_id": 1,
  "submission_content": "User's answer...",
  "score": 9,
  "feedback": "Great job!",
  "status": "graded"
}
```

---

### Get All Submissions for Exercise (Protected - Instructor)
**GET** `/exercises/{exerciseId}/submissions`

Query parameters:
- `page`: Page number (default: 1)

Response (200):
```json
{
  "data": [ ... ],
  "pagination": { ... }
}
```

---

### Grade Exercise Submission (Protected - Instructor)
**PUT** `/submissions/{submissionId}/grade`

Request body:
```json
{
  "score": 9,
  "feedback": "Excellent work!"
}
```

Response (200):
```json
{
  "message": "Submission graded successfully",
  "submission": { ... }
}
```

---

### Get Exercise Statistics (Public)
**GET** `/exercises/{exerciseId}/stats`

Response (200):
```json
{
  "completion_count": 45,
  "average_score": 8.5,
  "total_submissions": 50
}
```

---

## Quiz Endpoints

### Get Course Quizzes (Public)
**GET** `/courses/{courseId}/quizzes`

Response (200):
```json
[
  {
    "id": 1,
    "course_id": 1,
    "title": "Spanish Basics Assessment",
    "description": "Test your knowledge...",
    "passing_score": 70,
    "total_questions": 10,
    "time_limit_minutes": 20,
    "is_published": true
  }
]
```

---

### Get Quiz Details (Public)
**GET** `/quizzes/{id}`

Response (200):
```json
{
  "id": 1,
  "course_id": 1,
  "title": "Spanish Basics Assessment",
  "description": "Test your knowledge...",
  "passing_score": 70,
  "total_questions": 10,
  "time_limit_minutes": 20,
  "is_published": true,
  "questions": [
    {
      "id": 1,
      "quiz_id": 1,
      "question": "What does 'Hola' mean?",
      "question_type": "multiple_choice",
      "options": ["Hello", "Goodbye", "Thank you", "Please"],
      "points": 1,
      "order": 1
    }
  ]
}
```

---

### Create Quiz (Protected - Instructor Only)
**POST** `/quizzes`

Request body:
```json
{
  "course_id": 1,
  "title": "New Quiz",
  "description": "Quiz description",
  "passing_score": 75,
  "time_limit_minutes": 30,
  "show_results_immediately": true
}
```

Response (201):
```json
{
  "message": "Quiz created successfully",
  "quiz": { ... }
}
```

---

### Add Question to Quiz (Protected - Instructor Only)
**POST** `/quizzes/{quizId}/questions`

Request body:
```json
{
  "question": "What is 2 + 2?",
  "question_type": "multiple_choice",
  "options": ["3", "4", "5", "6"],
  "correct_answer": "4",
  "explanation": "Basic arithmetic",
  "points": 1,
  "order": 1
}
```

Response (201):
```json
{
  "message": "Question added successfully",
  "question": { ... }
}
```

---

### Submit Quiz (Protected)
**POST** `/quizzes/{id}/submit`

Request body:
```json
{
  "answers": {
    "1": "Hello",
    "2": "Buenos días",
    "3": "Cinco"
  }
}
```

Response (201):
```json
{
  "message": "Quiz submitted successfully",
  "result": {
    "id": 1,
    "user_id": 3,
    "quiz_id": 1,
    "score": 85,
    "total_questions": 3,
    "correct_answers": 3,
    "passed": true,
    "completed_at": "2024-01-15T10:00:00.000000Z"
  },
  "feedback": "Quiz completed!\n\nScore: 85%\nCorrect Answers: 3/3\nStatus: PASSED ✓\nGrade: B"
}
```

---

### Get Quiz Results (Protected)
**GET** `/quizzes/{id}/results`

Response (200):
```json
[
  {
    "id": 1,
    "user_id": 3,
    "quiz_id": 1,
    "score": 85,
    "total_questions": 3,
    "correct_answers": 3,
    "passed": true,
    "completed_at": "2024-01-15T10:00:00.000000Z"
  }
]
```

---

### Get Quiz Statistics (Public)
**GET** `/quizzes/{quizId}/stats`

Response (200):
```json
{
  "total_attempts": 45,
  "average_score": 78.5,
  "pass_rate": 82.22
}
```

---

## Progress & Dashboard Endpoints

### Get Dashboard (Protected)
**GET** `/dashboard`

Response (200):
```json
{
  "stats": {
    "total_courses": 3,
    "completed_courses": 1,
    "in_progress_courses": 2,
    "average_completion": 48.33,
    "total_quiz_attempts": 5,
    "quiz_pass_rate": 80
  },
  "enrollments": [
    {
      "id": 1,
      "user_id": 3,
      "course_id": 1,
      "status": "in_progress",
      "completion_percentage": 45,
      "course": { ... }
    }
  ]
}
```

---

### Get Course Progress Details (Protected)
**GET** `/courses/{courseId}/progress-details`

Response (200):
```json
{
  "enrollment": { ... },
  "lesson_progress": {
    "completed": 2,
    "total": 5,
    "percentage": 40
  },
  "quiz_results": {
    "total_attempts": 2,
    "passed": 1,
    "average_score": 82.5
  }
}
```

---

### Get Lesson Progress Details (Protected)
**GET** `/lessons/{lessonId}/progress-details`

Response (200):
```json
{
  "id": 1,
  "user_id": 3,
  "lesson_id": 1,
  "is_completed": false,
  "progress_percentage": 60,
  "started_at": "2024-01-15T10:00:00.000000Z"
}
```

---

### Get Quiz Progress (Protected)
**GET** `/quizzes/{quizId}/progress`

Response (200):
```json
{
  "attempts": [ ... ],
  "summary": {
    "total_attempts": 2,
    "best_score": 90,
    "latest_score": 85,
    "pass_rate": 100
  }
}
```

---

### Get Performance Report (Protected)
**GET** `/performance-report`

Response (200):
```json
{
  "user": { ... },
  "overall_stats": {
    "total_courses": 3,
    "total_quiz_attempts": 5,
    "average_quiz_score": 82.5,
    "quizzes_passed": 4,
    "pass_rate": 80
  },
  "course_performance": [
    {
      "course_id": 1,
      "course_title": "Spanish for Beginners",
      "status": "in_progress",
      "completion_percentage": 45
    }
  ],
  "recent_quiz_results": [ ... ]
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "error": "Course not found"
}
```

### 500 Server Error
```json
{
  "message": "Server error message"
}
```

---

## Test Accounts

### Admin
- Email: `admin@languageapp.com`
- Password: `password123`

### Instructor (Spanish)
- Email: `maria@languageapp.com`
- Password: `password123`

### Instructor (French)
- Email: `pierre@languageapp.com`
- Password: `password123`

### Student 1
- Email: `john@example.com`
- Password: `password123`

### Student 2
- Email: `emma@example.com`
- Password: `password123`

---

## Tips for API Testing

1. **Using cURL:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

2. **Using Postman:**
   - Import the collection from the repository
   - Set the base URL to `http://localhost:8000/api`
   - Use the Authorization tab to set the Bearer token

3. **Using Thunder Client (VS Code):**
   - Use the `api-test.http` file in the project root

---

## Rate Limiting

No rate limiting is currently implemented. For production, consider adding rate limiting middleware.

---

## Pagination

List endpoints support pagination:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15)

Example:
```
GET /courses?page=2&per_page=10
```

---

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Eloquent ORM](https://laravel.com/docs/eloquent)
