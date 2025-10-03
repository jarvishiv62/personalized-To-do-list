<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Display the chatbot interface.
     */
    public function index(): View
    {
        // Get recent chat history (last 20 messages)
        $chatHistory = ChatHistory::recent(20)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.index', compact('chatHistory'));
    }

    /**
     * Process user message and return bot response.
     */
    public function message(Request $request, ChatbotService $chatbot): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');

        // Process message through chatbot service
        $response = $chatbot->processMessage($userMessage);

        // Save to chat history
        $chatHistory = ChatHistory::create([
            'user_id' => null, // For future multi-user support
            'message' => $userMessage,
            'response' => $response['text'],
        ]);

        return response()->json([
            'success' => true,
            'response' => $response['text'],
            'links' => $response['links'] ?? [],
            'ai_powered' => $response['ai_powered'] ?? false,
            'timestamp' => $chatHistory->created_at->format('g:i A'),
        ]);
    }

    /**
     * Clear chat history.
     */
    public function clear(): JsonResponse
    {
        ChatHistory::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Chat history cleared successfully.',
        ]);
    }

    /**
     * Get chat history.
     */
    public function history(): JsonResponse
    {
        $chatHistory = ChatHistory::recent(50)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'response' => $chat->response,
                    'timestamp' => $chat->created_at->format('g:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'history' => $chatHistory,
        ]);
    }
}