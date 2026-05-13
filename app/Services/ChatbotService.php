<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ChatbotService
{
    public function getResponse(string $message, ?User $user = null, array $context = []): array
    {
        $message = Str::lower(trim($message));

        if ($message === '') {
            return $this->welcomeResponse($user);
        }

        if ($this->containsAny($message, ['hi', 'hello', 'hey', 'start'])) {
            return $this->welcomeResponse($user);
        }

        if ($this->containsAny($message, ['recommend', 'suggest', 'popular', 'trending', 'best'])) {
            return $this->recommendationResponse($user);
        }

        if ($this->containsAny($message, ['course', 'learn', 'find', 'search', 'show', 'browse', 'language'])) {
            return $this->courseSearchResponse($message);
        }

        if ($this->containsAny($message, ['progress', 'completed', 'status', 'continue'])) {
            return $this->progressResponse($user);
        }

        if ($this->containsAny($message, ['enroll', 'join', 'signup', 'sign up', 'register'])) {
            return $this->enrollmentResponse($user);
        }

        if ($this->containsAny($message, ['quiz', 'exercise', 'lesson', 'practice'])) {
            return $this->learningToolsResponse();
        }

        if ($this->containsAny($message, ['badge', 'achievement', 'reward'])) {
            return $this->badgeResponse($user);
        }

        if ($this->containsAny($message, ['help', 'how', 'what can', 'guide'])) {
            return $this->helpResponse();
        }

        return [
            'type' => 'default',
            'text' => 'I can help you find a course, choose a level, explain enrollment, or check learning progress. Try asking for Hindi courses, beginner courses, recommendations, or help.',
            'actions' => $this->defaultActions($user),
            'suggestions' => $this->getSuggestions($user),
        ];
    }

    public function getSuggestions(?User $user): array
    {
        if (!$user) {
            return ['Show courses', 'Recommend courses', 'How to enroll', 'Help'];
        }

        return ['Continue learning', 'My progress', 'Recommend courses', 'Help'];
    }

    public function searchCourses(string $query): Collection
    {
        return $this->courseQuery($query)
            ->limit(10)
            ->get(['id', 'title', 'description', 'level', 'language', 'thumbnail']);
    }

    public function getRecommendations(?User $user): array
    {
        if (!$user) {
            return Course::where('is_published', true)
                ->orderByDesc('rating')
                ->orderBy('title')
                ->limit(5)
                ->get(['id', 'title', 'description', 'level', 'language', 'thumbnail'])
                ->toArray();
        }

        $enrolledIds = $user->enrollments()->pluck('course_id')->all();
        $languages = Course::whereIn('id', $enrolledIds)->pluck('language')->unique();

        $query = Course::where('is_published', true)
            ->whereNotIn('id', $enrolledIds);

        if ($languages->isNotEmpty()) {
            $query->whereIn('language', $languages);
        }

        $courses = $query->orderByDesc('rating')
            ->orderBy('title')
            ->limit(5)
            ->get(['id', 'title', 'description', 'level', 'language', 'thumbnail']);

        if ($courses->isEmpty()) {
            $courses = Course::where('is_published', true)
                ->whereNotIn('id', $enrolledIds)
                ->orderBy('title')
                ->limit(5)
                ->get(['id', 'title', 'description', 'level', 'language', 'thumbnail']);
        }

        return $courses->toArray();
    }

    public function getHelpTopics(): array
    {
        return [
            [
                'title' => 'Find courses',
                'topics' => ['Search by language', 'Filter by level', 'Open a course page'],
            ],
            [
                'title' => 'Start learning',
                'topics' => ['Create an account', 'Enroll in a course', 'Open lessons and quizzes'],
            ],
            [
                'title' => 'Track progress',
                'topics' => ['View dashboard', 'Check completed lessons', 'Review quiz attempts'],
            ],
        ];
    }

    private function welcomeResponse(?User $user): array
    {
        $name = $user ? $user->name : 'there';

        return [
            'type' => 'welcome',
            'text' => "Hi {$name}. I can help you choose a language course, open lessons, explain enrollment, and track progress.",
            'actions' => $this->defaultActions($user),
            'suggestions' => $this->getSuggestions($user),
        ];
    }

    private function courseSearchResponse(string $message): array
    {
        $courses = $this->courseQuery($message)
            ->limit(6)
            ->get(['id', 'title', 'description', 'level', 'language', 'thumbnail'])
            ->map(fn (Course $course) => $this->coursePayload($course))
            ->values();

        if ($courses->isEmpty()) {
            $courses = Course::where('is_published', true)
                ->orderBy('title')
                ->limit(6)
                ->get(['id', 'title', 'description', 'level', 'language', 'thumbnail'])
                ->map(fn (Course $course) => $this->coursePayload($course))
                ->values();
        }

        return [
            'type' => 'courses',
            'text' => 'Here are courses you can explore. Pick one to open its full details.',
            'courses' => $courses,
            'actions' => [
                ['label' => 'Open catalog', 'url' => route('courses.index')],
                ['label' => 'Beginner courses', 'message' => 'Show beginner courses'],
                ['label' => 'Recommendations', 'message' => 'Recommend courses'],
            ],
            'suggestions' => ['Show beginner courses', 'Show Hindi courses', 'Recommend courses'],
        ];
    }

    private function recommendationResponse(?User $user): array
    {
        $courses = collect($this->getRecommendations($user))
            ->map(fn (array $course) => [
                'id' => $course['id'],
                'title' => $course['title'],
                'description' => Str::limit($course['description'] ?? '', 100),
                'level' => $course['level'],
                'language' => $course['language'],
                'thumbnail' => $course['thumbnail'] ?? null,
                'url' => route('courses.show', $course['id']),
            ])
            ->values();

        return [
            'type' => 'recommendations',
            'text' => $user
                ? 'Based on your learning activity, these are good next courses.'
                : 'These are good courses to start with.',
            'courses' => $courses,
            'actions' => [
                ['label' => 'Open catalog', 'url' => route('courses.index')],
                ['label' => 'How to enroll', 'message' => 'How do I enroll?'],
            ],
            'suggestions' => ['Show courses', 'How to enroll', 'Help'],
        ];
    }

    private function progressResponse(?User $user): array
    {
        if (!$user) {
            return [
                'type' => 'progress',
                'text' => 'Log in to see your course progress, completed lessons, and quiz history.',
                'actions' => [
                    ['label' => 'Login', 'url' => route('login')],
                    ['label' => 'Create account', 'url' => route('register')],
                    ['label' => 'Browse courses', 'url' => route('courses.index')],
                ],
                'suggestions' => ['Show courses', 'How to enroll'],
            ];
        }

        $enrollments = $user->enrollments()
            ->with('course')
            ->latest()
            ->get()
            ->map(fn ($enrollment) => [
                'course' => $enrollment->course->title,
                'progress' => (float) $enrollment->completion_percentage,
                'status' => Str::headline($enrollment->status),
                'url' => route('courses.show', $enrollment->course_id),
            ]);

        if ($enrollments->isEmpty()) {
            return [
                'type' => 'progress',
                'text' => 'You are not enrolled in any courses yet. Start with a beginner course or browse the catalog.',
                'actions' => [
                    ['label' => 'Browse courses', 'url' => route('courses.index')],
                    ['label' => 'Recommend courses', 'message' => 'Recommend courses'],
                ],
                'suggestions' => ['Recommend courses', 'Show beginner courses'],
            ];
        }

        return [
            'type' => 'progress',
            'text' => 'Here is your current learning progress.',
            'enrollments' => $enrollments,
            'actions' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Browse courses', 'url' => route('courses.index')],
            ],
            'suggestions' => ['Continue learning', 'Recommend courses'],
        ];
    }

    private function enrollmentResponse(?User $user): array
    {
        return [
            'type' => 'enrollment',
            'text' => $user
                ? "Open a course, click Enroll, then start with the first lesson. Your progress will be saved automatically."
                : "Create an account or log in, open any course, then click Enroll to start tracking lessons and quizzes.",
            'actions' => $user
                ? [
                    ['label' => 'Browse courses', 'url' => route('courses.index')],
                    ['label' => 'Recommend courses', 'message' => 'Recommend courses'],
                ]
                : [
                    ['label' => 'Create account', 'url' => route('register')],
                    ['label' => 'Login', 'url' => route('login')],
                    ['label' => 'Browse courses', 'url' => route('courses.index')],
                ],
            'suggestions' => ['Show courses', 'Recommend courses', 'Help'],
        ];
    }

    private function learningToolsResponse(): array
    {
        return [
            'type' => 'tools',
            'text' => 'Each course can include lessons, exercises, and quizzes. Lessons teach the topic, exercises help you practice, and quizzes check understanding.',
            'actions' => [
                ['label' => 'Browse courses', 'url' => route('courses.index')],
                ['label' => 'Recommend a course', 'message' => 'Recommend courses'],
            ],
            'suggestions' => ['Show courses', 'How to enroll', 'My progress'],
        ];
    }

    private function badgeResponse(?User $user): array
    {
        if (!$user) {
            return [
                'type' => 'badges',
                'text' => 'Badges are earned by completing lessons, passing quizzes, and reaching milestones. Sign in to start collecting them.',
                'actions' => [
                    ['label' => 'Create account', 'url' => route('register')],
                    ['label' => 'Browse courses', 'url' => route('courses.index')],
                ],
                'suggestions' => ['Show courses', 'How to enroll'],
            ];
        }

        $badges = $user->badges()->limit(6)->get(['badges.id', 'badges.name']);

        return [
            'type' => 'badges',
            'text' => $badges->isEmpty()
                ? 'You have not earned badges yet. Complete lessons and quizzes to unlock achievements.'
                : 'Here are your latest badges.',
            'badges' => $badges,
            'actions' => [
                ['label' => 'Browse courses', 'url' => route('courses.index')],
                ['label' => 'My progress', 'message' => 'Show my progress'],
            ],
            'suggestions' => ['My progress', 'Recommend courses'],
        ];
    }

    private function helpResponse(): array
    {
        return [
            'type' => 'help',
            'text' => "I can help with course search, recommendations, enrollment steps, progress tracking, lessons, quizzes, and achievements.",
            'topics' => $this->getHelpTopics(),
            'actions' => [
                ['label' => 'Browse courses', 'url' => route('courses.index')],
                ['label' => 'Recommend courses', 'message' => 'Recommend courses'],
                ['label' => 'How to enroll', 'message' => 'How do I enroll?'],
            ],
            'suggestions' => ['Show courses', 'Recommend courses', 'My progress'],
        ];
    }

    private function courseQuery(string $message)
    {
        $clean = preg_replace('/\b(course|courses|learn|find|search|show|browse|me|all|the|a|an|language|languages)\b/', ' ', $message);
        $keywords = collect(preg_split('/\s+/', trim($clean)))
            ->filter(fn ($word) => strlen($word) > 1)
            ->values();

        $query = Course::where('is_published', true);

        if ($keywords->isEmpty()) {
            return $query->orderBy('title');
        }

        return $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('language', 'like', "%{$keyword}%")
                    ->orWhere('level', 'like', "%{$keyword}%");
            }
        })->orderBy('title');
    }

    private function coursePayload(Course $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'description' => Str::limit($course->description, 100),
            'level' => $course->level,
            'language' => $course->language,
            'thumbnail' => $course->thumbnail,
            'url' => route('courses.show', $course),
        ];
    }

    private function defaultActions(?User $user): array
    {
        $actions = [
            ['label' => 'Browse courses', 'url' => route('courses.index')],
            ['label' => 'Recommend courses', 'message' => 'Recommend courses'],
            ['label' => 'How to enroll', 'message' => 'How do I enroll?'],
        ];

        if (!$user) {
            $actions[] = ['label' => 'Create account', 'url' => route('register')];
        }

        return $actions;
    }

    private function containsAny(string $message, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($message, $needle)) {
                return true;
            }
        }

        return false;
    }
}
