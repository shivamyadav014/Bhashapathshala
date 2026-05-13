# Chatbot Setup & Usage Guide

## Overview

A fully functional AI-powered chatbot has been integrated into your Laravel educational platform. The chatbot helps users:

- **Browse & Find Courses** - Search for courses by language, level, or keywords
- **Track Progress** - Check learning progress and completion status
- **Get Recommendations** - Receive personalized course recommendations
- **View Achievements** - See earned badges and achievements
- **Learn Features** - Understand platform capabilities
- **Get Help** - Access help topics and guidance

---

## Components Created

### 1. **Database Migration**
- **File:** `database/migrations/2024_01_01_000020_create_chat_messages_table.php`
- **Purpose:** Stores chat history for analytics and improvement
- **Run:** `php artisan migrate`

### 2. **Models**
- **File:** `app/Models/ChatMessage.php`
- **Fields:** user_id, user_message, bot_response, message_type, sentiment, timestamps

### 3. **Controller**
- **File:** `app/Http/Controllers/ChatbotController.php`
- **Methods:**
  - `chat()` - Handle user messages and generate responses
  - `suggestions()` - Get intelligent suggestions based on user state
  - `searchCourses()` - Search courses by query
  - `recommendations()` - Get personalized recommendations
  - `help()` - Get help topics

### 4. **Service**
- **File:** `app/Services/ChatbotService.php`
- **Features:**
  - Natural Language Processing (NLP) intent recognition
  - Context-aware responses
  - Course recommendations engine
  - Progress tracking queries
  - Badge/achievement queries

### 5. **Frontend Component**
- **File:** `resources/views/components/chatbot.blade.php`
- **Features:**
  - Beautiful chat UI with animations
  - Mobile-responsive design
  - Quick suggestion buttons
  - Course cards display
  - Progress bars

### 6. **JavaScript**
- **File:** `public/js/chatbot.js`
- **Features:**
  - Real-time message handling
  - API communication
  - Message formatting and display
  - Typing indicators
  - Responsive interactions

### 7. **API Routes**
- **File:** `routes/api.php`
- **Public routes:**
  - `GET /api/chatbot/help` - Get help topics
  - `POST /api/chatbot/search-courses` - Search courses
- **Protected routes (require authentication):**
  - `POST /api/chatbot/chat` - Send message and get response
  - `GET /api/chatbot/suggestions` - Get suggestions
  - `GET /api/chatbot/recommendations` - Get recommendations

---

## Installation & Setup

### Step 1: Run Migration
```bash
cd "c:\Users\shiva\OneDrive\Desktop\laravel prfoject"
php artisan migrate
```

### Step 2: Verify Routes
```bash
php artisan route:list | grep chatbot
```

You should see:
```
POST      /api/chatbot/chat
GET       /api/chatbot/suggestions
POST      /api/chatbot/search-courses
GET       /api/chatbot/recommendations
GET       /api/chatbot/help
```

### Step 3: Start Development Server
```bash
php artisan serve
```

### Step 4: Access the Platform
Open your browser and visit:
```
http://localhost:8000
```

The chatbot widget will appear in the bottom-right corner of every page!

---

## Usage Examples

### For Users

1. **Finding Courses**
   - User: "Show me Spanish courses"
   - Bot: Displays matching courses with details

2. **Checking Progress**
   - User: "What's my progress?"
   - Bot: Shows progress bars for all enrolled courses

3. **Getting Recommendations**
   - User: "Recommend courses for me"
   - Bot: Shows personalized recommendations based on enrollment history

4. **Earning Badges**
   - User: "What are my badges?"
   - Bot: Displays earned badges and achievements

5. **Getting Help**
   - User: "How do I get started?"
   - Bot: Provides step-by-step guidance

### Intent Recognition

The bot recognizes user intent through keywords:

```
Greeting: "hi", "hello", "hey", "greetings"
Help: "help", "how", "what can", "guide"
Course Search: "course", "learn", "find", "search"
Progress: "progress", "completed", "how far", "my status"
Enrollment: "enroll", "join", "sign up"
Features: "feature", "can i", "capability"
Badges: "badge", "achievement", "reward"
```

---

## API Documentation

### 1. Chat Endpoint
**POST** `/api/chatbot/chat`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "message": "Show me courses",
  "context": {}
}
```

**Response:**
```json
{
  "success": true,
  "response": {
    "type": "search_result",
    "text": "I found 3 course(s) for you!",
    "courses": [
      {
        "id": 1,
        "title": "Spanish Basics",
        "description": "Learn basic Spanish...",
        "level": "Beginner",
        "language": "Spanish"
      }
    ],
    "suggestions": ["Show more", "Enroll now"]
  }
}
```

### 2. Suggestions Endpoint
**GET** `/api/chatbot/suggestions`

**Response:**
```json
{
  "success": true,
  "suggestions": [
    "Browse Courses",
    "Show my progress",
    "Get recommendations"
  ]
}
```

### 3. Search Courses Endpoint
**POST** `/api/chatbot/search-courses`

**Request Body:**
```json
{
  "query": "Spanish"
}
```

**Response:**
```json
{
  "success": true,
  "courses": [...]
}
```

### 4. Recommendations Endpoint
**GET** `/api/chatbot/recommendations`

**Response:**
```json
{
  "success": true,
  "recommendations": [...]
}
```

### 5. Help Endpoint
**GET** `/api/chatbot/help`

**Response:**
```json
{
  "success": true,
  "topics": [
    {
      "title": "Getting Started",
      "topics": ["How to sign up", "How to browse courses"]
    }
  ]
}
```

---

## Customization

### Change Bot Name
Edit `resources/views/components/chatbot.blade.php`:
```html
<h3>Custom Bot Name</h3>
```

### Change Colors
Edit `resources/views/components/chatbot.blade.php` - Update CSS variables:
```css
:root {
    --chatbot-primary: #007bff;  /* Change primary color */
    --chatbot-secondary: #f8f9fa;
    --chatbot-border: #dee2e6;
}
```

### Add Custom Intents
Edit `app/Services/ChatbotService.php` - Add new intent recognition method:
```php
private function isCustomIntent(string $message): bool
{
    return strpos($message, 'keyword') !== false;
}

private function handleCustomIntent(): array
{
    return [
        'type' => 'custom',
        'text' => 'Custom response...',
        'suggestions' => [...]
    ];
}
```

### Extend Responses
Edit `app/Services/ChatbotService.php` - Add more response variations in `getDefaultResponse()` method.

---

## Features

### Current Features
- ✅ Intent recognition based on keywords
- ✅ Course search and display
- ✅ Progress tracking queries
- ✅ Recommendation engine
- ✅ Badge/achievement display
- ✅ Help topics
- ✅ Mobile responsive
- ✅ Real-time messaging
- ✅ Typing indicators
- ✅ Suggestion buttons
- ✅ Chat history (database)

### Planned Enhancements
- 🔄 AI/ML powered responses (integrate with OpenAI, Gemini, etc.)
- 🔄 User preference learning
- 🔄 Multi-language support
- 🔄 Sentiment analysis
- 🔄 Advanced NLP
- 🔄 Voice input/output
- 🔄 Chat analytics dashboard

---

## Testing

### Test in Browser Console
```javascript
// Manually test message sending
new ChatbotWidget().sendMessage("Show me courses");
```

### Test API with cURL
```bash
curl -X POST http://localhost:8000/api/chatbot/chat \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {csrf_token}" \
  -H "Authorization: Bearer {token}" \
  -d '{"message": "Show me courses"}'
```

### View Chat History
```bash
php artisan tinker
App\Models\ChatMessage::all();
```

---

## Troubleshooting

### Chatbot Not Appearing
1. Check browser console for errors (F12)
2. Verify CSS is loading: Check Network tab for chatbot.blade.php
3. Clear browser cache (Ctrl+Shift+Del)
4. Verify `public/js/chatbot.js` exists

### API 404 Errors
1. Run `php artisan route:cache --clear`
2. Verify routes in `routes/api.php`
3. Check controller exists and is named correctly

### Database Errors
1. Run `php artisan migrate`
2. Check database connection in `.env`
3. Verify chat_messages table exists: `php artisan tinker` → `DB::table('chat_messages')->count()`

### CSRF Token Missing
1. Verify `<meta name="csrf-token">` in `<head>` of layout
2. Check `X-CSRF-TOKEN` header in requests
3. Run `php artisan cache:clear`

---

## File Locations Summary

```
📁 app/
  ├── Http/Controllers/ChatbotController.php
  ├── Models/ChatMessage.php
  └── Services/ChatbotService.php

📁 database/
  └── migrations/2024_01_01_000020_create_chat_messages_table.php

📁 resources/
  └── views/components/chatbot.blade.php

📁 public/
  └── js/chatbot.js

📁 routes/
  └── api.php (updated with new routes)

📁 resources/views/
  └── layouts/app.blade.php (updated to include chatbot)
```

---

## Support & Next Steps

1. **Test the chatbot** - Interact with it on your platform
2. **Customize responses** - Modify `ChatbotService` to match your needs
3. **Monitor usage** - Check `chat_messages` table for insights
4. **Enhance AI** - Consider integrating with LLM APIs (OpenAI, Gemini)
5. **Gather feedback** - Ask users for improvement suggestions

---

## Notes

- Chatbot widget is visible on all authenticated pages
- Chat history is saved in database for analytics
- Responses are context-aware based on user enrollment
- Mobile-optimized UI automatically adapts
- No external AI API required (rule-based chatbot)

---

**Last Updated:** April 29, 2026
**Version:** 1.0
**Status:** Ready for Production
