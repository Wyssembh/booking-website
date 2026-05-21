<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class ChatbotController extends Controller
{
    public function reply(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee.']);
            return;
        }

        $payload = $this->readJsonPayload();
        $messages = $this->normalizeMessages($payload);

        if ($messages === []) {
            $fallbackMessage = trim((string) ($_POST['message'] ?? ''));
            if ($fallbackMessage !== '') {
                $messages[] = ['role' => 'user', 'content' => $fallbackMessage];
            }
        }

        if ($messages === []) {
            http_response_code(400);
            echo json_encode(['error' => 'Message manquant.']);
            return;
        }

        $apiKey = $this->getApiKey();
        if ($apiKey === '') {
            http_response_code(500);
            echo json_encode(['error' => 'GROQ_API_KEY manquant.']);
            return;
        }

        $model = $this->getModel();
        $systemPrompt = $this->systemPrompt();

        $requestBody = [
            'model' => $model,
            'messages' => array_merge([
                ['role' => 'system', 'content' => $systemPrompt],
            ], $messages),
            'temperature' => 0.4,
            'max_tokens' => 300,
        ];

        $result = $this->callGroq($apiKey, $requestBody);
        if ($result['error'] !== '') {
            http_response_code(500);
            echo json_encode(['error' => $result['error']]);
            return;
        }

        echo json_encode(['reply' => $result['reply']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function readJsonPayload(): array
    {
        $raw = file_get_contents('php://input');
        if (!is_string($raw) || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function normalizeMessages(array $payload): array
    {
        $rawMessages = $payload['messages'] ?? [];
        if (!is_array($rawMessages)) {
            return [];
        }

        $messages = [];
        foreach ($rawMessages as $item) {
            if (!is_array($item)) {
                continue;
            }

            $role = (string) ($item['role'] ?? '');
            if (!in_array($role, ['user', 'assistant'], true)) {
                continue;
            }

            $content = trim((string) ($item['content'] ?? ''));
            if ($content === '') {
                continue;
            }

            if (strlen($content) > 1200) {
                $content = substr($content, 0, 1200);
            }

            $messages[] = ['role' => $role, 'content' => $content];
        }

        if (count($messages) > 8) {
            $messages = array_slice($messages, -8);
        }

        return $messages;
    }

    private function systemPrompt(): string
    {
        return 'Tu es un assistant de reservation pour un site de hotels. '
            . 'Reponds en francais, en restant concis. '
            . 'Aide pour les hotels, disponibilites, tarifs, services et conseils de sejour. '
            . 'Si la question sort du contexte, recentre la reponse sur les hotels et reservations.';
    }

    private function getApiKey(): string
    {
        return $this->getEnvValue('GROQ_API_KEY');
    }

    private function getModel(): string
    {
        $model = $this->getEnvValue('GROQ_MODEL');
        return $model !== '' ? $model : 'llama-3.1-8b-instant';
    }

    private function getEnvValue(string $key): string
    {
        $value = getenv($key);
        if ($value !== false && trim((string) $value) !== '') {
            return trim((string) $value);
        }

        $envPath = dirname(__DIR__, 3) . '/.env';
        if (!is_file($envPath)) {
            return '';
        }

        $env = parse_ini_file($envPath, false, INI_SCANNER_RAW) ?: [];
        if (!isset($env[$key])) {
            return '';
        }

        if (!is_string($env[$key])) {
            return '';
        }

        return trim($env[$key], " \t\n\r\0\x0B\"");
    }

    private function callGroq(string $apiKey, array $requestBody): array
    {
        $endpoint = 'https://api.groq.com/openai/v1/chat/completions';
        $body = json_encode($requestBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($body === false) {
            return ['reply' => '', 'error' => 'Erreur de preparation de la requete.'];
        }

        $handle = curl_init($endpoint);
        if ($handle === false) {
            return ['reply' => '', 'error' => 'cURL indisponible.'];
        }

        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_TIMEOUT => 20,
            CURLOPT_POSTFIELDS => $body,
        ]);

        $response = curl_exec($handle);
        if ($response === false) {
            $error = curl_error($handle);
            curl_close($handle);
            return ['reply' => '', 'error' => 'Erreur de connexion: ' . $error];
        }

        $status = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        $payload = json_decode($response, true);
        if ($status < 200 || $status >= 300) {
            $message = '';
            if (is_array($payload)) {
                $message = (string) ($payload['error']['message'] ?? '');
            }

            return ['reply' => '', 'error' => $message !== '' ? $message : 'Erreur API Groq.'];
        }

        $reply = '';
        if (is_array($payload)) {
            $reply = trim((string) ($payload['choices'][0]['message']['content'] ?? ''));
        }

        if ($reply === '') {
            return ['reply' => '', 'error' => 'Reponse vide.'];
        }

        return ['reply' => $reply, 'error' => ''];
    }
}
