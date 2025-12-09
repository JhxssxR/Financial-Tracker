<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        // Try Gemini first (more generous free tier), fallback to OpenAI
        $this->apiKey = config('services.gemini.api_key') ?: config('services.openai.api_key');
        // Using Gemini 2.5 Flash for better performance and quotas
        $this->apiUrl = config('services.gemini.api_key') 
            ? 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent'
            : 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Send a message to the chatbot and get a response
     *
     * @param string $message
     * @param array $context Additional context (user data, transactions, etc.)
     * @return array
     */
    public function sendMessage(string $message, array $context = []): array
    {
        try {
            // Build the system message with financial context
            $systemMessage = $this->buildSystemMessage($context);

            // Check which API we're using
            $isGemini = strpos($this->apiUrl, 'generativelanguage.googleapis.com') !== false;

            if ($isGemini) {
                // Gemini API format
                $prompt = $systemMessage . "\n\nUser Question: " . $message;
                
                $response = Http::timeout(30)->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 500,
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
                    
                    return [
                        'success' => true,
                        'message' => $text,
                    ];
                }
            } else {
                // OpenAI API format
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(30)->post($this->apiUrl, [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemMessage
                        ],
                        [
                            'role' => 'user',
                            'content' => $message
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['choices'][0]['message']['content'] ?? 'No response';
                    
                    return [
                        'success' => true,
                        'message' => $text,
                    ];
                }
            }

            Log::error('AI API Error: ' . $response->status() . ' - ' . $response->body());
            
            return [
                'success' => false,
                'message' => 'I\'m having trouble connecting to my AI service right now. Please try again in a moment.',
            ];

        } catch (\Exception $e) {
            Log::error('Chatbot Service Error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => 'I apologize, but I encountered an issue. Please try again.',
            ];
        }
    }

    /**
     * Build a context-aware system message for financial queries
     *
     * @param array $context
     * @return string
     */
    private function buildSystemMessage(array $context): string
    {
        $systemMessage = "You are a helpful financial assistant for a personal finance tracking application. ";
        $systemMessage .= "Help users understand their finances, provide budgeting advice, and answer financial questions. ";
        $systemMessage .= "Be concise, friendly, and accurate. Use emojis occasionally to make responses engaging.";

        if (!empty($context)) {
            $systemMessage .= "\n\nUser Financial Context:\n";
            
            if (isset($context['total_income'])) {
                $systemMessage .= "- Total Income: " . $context['total_income'] . "\n";
            }
            if (isset($context['total_expenses'])) {
                $systemMessage .= "- Total Expenses: " . $context['total_expenses'] . "\n";
            }
            if (isset($context['budget'])) {
                $systemMessage .= "- Budget: " . $context['budget'] . "\n";
            }
            if (isset($context['savings'])) {
                $systemMessage .= "- Savings: " . $context['savings'] . "\n";
            }
            if (isset($context['top_categories'])) {
                $systemMessage .= "- Top Spending Categories: " . implode(', ', $context['top_categories']) . "\n";
            }
            if (isset($context['net_savings'])) {
                $systemMessage .= "- Net Savings This Month: " . $context['net_savings'] . "\n";
            }
        }

        return $systemMessage;
    }

    /**
     * Get financial insights based on user data
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getFinancialInsights($user): array
    {
        // Gather user financial data
        $totalIncome = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('date', now()->month)
            ->sum('amount');

        $totalExpenses = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->sum('amount');

        $topCategories = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(fn($group) => $group->sum('amount'))
            ->sortDesc()
            ->take(3)
            ->keys()
            ->toArray();

        $context = [
            'total_income' => number_format($totalIncome, 2),
            'total_expenses' => number_format($totalExpenses, 2),
            'net_savings' => number_format($totalIncome - $totalExpenses, 2),
            'top_categories' => $topCategories
        ];

        $message = "Based on my financial data for this month, what insights can you provide? Any recommendations for improvement?";

        return $this->sendMessage($message, $context);
    }
}
