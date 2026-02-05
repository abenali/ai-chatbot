<?php

namespace App\Controller\Api;

use App\Service\GeminiService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ChatController extends AbstractController
{
    public function __construct(
        private readonly GeminiService $geminiService
    )
    {
    }

    /**
     * Test de l'API
     */
    #[Route('/chat/test', name: 'chat_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        $isConnected = $this->geminiService->testConnection();

        return $this->json([
            'status' => $isConnected ? 'connected' : 'error',
            'message' => $isConnected
                ? 'Connexion à Gemini réussie !'
                : 'Erreur de connexion à Gemini',
        ]);
    }

    /**
     * Endpoint principal du chatbot
     * @throws JsonException
     */
    #[Route('/chat', name: 'chat', methods: ['POST'])]
    public function chat(Request $request): JsonResponse
    {
        // Récupérer le message de l'utilisateur
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $userMessage = $data['message'] ?? '';

        // Validation
        if (empty($userMessage)) {
            return $this->json(
                [
                    'error' => 'Le message ne peut pas être vide',
                ],
                400
            );
        }

        // Appeler Gemini
        $result = $this->geminiService->generateText($userMessage);

        if (!$result['success']) {
            return $this->json(
                [
                    'error' => $result['error'],
                    'details' => $result['details'] ?? null,
                ],
                500
            );
        }

        // Retourner la réponse
        return $this->json([
            'user_message' => $userMessage,
            'ai_response' => $result['text'],
            'timestamp' => new \DateTime(),
        ]);
    }

    /**
     * Endpoint avec contexte (pour plus tard)
     * @throws JsonException
     */
    #[Route('/chat/context', name: 'chat_context', methods: ['POST'])]
    public function chatWithContext(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $userMessage = $data['message'] ?? '';
        $context = $data['context'] ?? '';

        if (empty($userMessage)) {
            return $this->json(['error' => 'Message vide'], 400);
        }

        // Construire le prompt avec contexte
        $fullPrompt = !empty($context)
            ? "Contexte: $context\n\nQuestion: $userMessage"
            : $userMessage;

        $result = $this->geminiService->generateText($fullPrompt);

        if (!$result['success']) {
            return $this->json(['error' => $result['error']], 500);
        }

        return $this->json([
            'user_message' => $userMessage,
            'ai_response' => $result['text'],
        ]);
    }
}