<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BlogApiClient
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $blogApiUrl,
        private readonly string $blogApiKey,
    ) {
    }

    public function isConfigured(): bool
    {
        return $this->blogApiUrl !== '' && $this->blogApiKey !== '';
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listPosts(int $limit = 20, int $offset = 0): array
    {
        $data = $this->request('/api/posts', [
            'limit' => $limit,
            'offset' => $offset,
        ]);

        $posts = $data['data'] ?? [];

        return is_array($posts) ? array_values($posts) : [];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPost(string $slug): ?array
    {
        $data = $this->request('/api/posts/' . rawurlencode($slug));
        $post = $data['data'] ?? null;

        return is_array($post) ? $post : null;
    }

    /**
     * @param array<string, scalar> $query
     * @return array<string, mixed>
     */
    private function request(string $path, array $query = []): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        try {
            $response = $this->httpClient->request(
                'GET',
                rtrim($this->blogApiUrl, '/') . $path,
                [
                    'headers' => [
                        'X-API-Key' => $this->blogApiKey,
                        'Accept' => 'application/json',
                    ],
                    'query' => $query,
                    'timeout' => 10,
                ]
            );

            $payload = $response->toArray(false);

            return is_array($payload) ? $payload : [];
        } catch (\Throwable) {
            return [];
        }
    }
}
