<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GeminiService
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        string $geminiApiKey,
        string $geminiApiUrl
    ) {
        $this->apiKey = $geminiApiKey;
        $this->apiUrl = $geminiApiUrl;
    }

    /**
     * Génère du texte via l'API Gemini
     */
    public function generateText(string $prompt, array $options = []): array
    {
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['maxTokens'] ?? 1000;

        try {
            $response = $this->httpClient->request('POST', $this->apiUrl, [
                'query' => [
                    'key' => $this->apiKey,
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => $maxTokens,
                    ],
                ],
            ]);

            $data = $response->toArray();

            // Extraire le texte de la réponse
            $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return [
                'success' => true,
                'text' => $generatedText,
                'data' => $data, // Données brutes pour debug
            ];

        } catch (TransportExceptionInterface $e) {
            return [
                'success' => false,
                'error' => 'Erreur de connexion à l\'API Gemini',
                'details' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération de texte',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test de connexion à l'API
     */
    public function testConnection(): bool
    {
        $result = $this->generateText('Dis juste "OK" si tu me comprends');
        return $result['success'] ?? false;
    }
}