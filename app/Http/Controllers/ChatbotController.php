<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Get chatbot response for user message
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'context' => 'nullable|array',
        ]);

        $user = $request->user();
        $response = $this->chatbotService->getResponse(
            $validated['message'],
            $user,
            $validated['context'] ?? []
        );

        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    }

    /**
     * Get suggestions for user
     */
    public function suggestions(Request $request): JsonResponse
    {
        $user = $request->user();
        $suggestions = $this->chatbotService->getSuggestions($user);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Search courses by query
     */
    public function searchCourses(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|max:100',
        ]);

        $courses = $this->chatbotService->searchCourses($validated['query']);

        return response()->json([
            'success' => true,
            'courses' => $courses,
        ]);
    }

    /**
     * Get course recommendations
     */
    public function recommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        $recommendations = $this->chatbotService->getRecommendations($user);

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations,
        ]);
    }

    /**
     * Get help topics
     */
    public function help(): JsonResponse
    {
        $topics = $this->chatbotService->getHelpTopics();

        return response()->json([
            'success' => true,
            'topics' => $topics,
        ]);
    }
}
