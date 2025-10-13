<?php
namespace modules\game\models;

class Search
{
    private $apiKey = "8bc47dab600645ac9164f534d0182baf";
    private $apiBase = "https://api.rawg.io/api/games";

    public function findGames(string $query, int $pageSize = 10): array
    {
        if (empty($query)) return [];

        $params = [
            'key' => $this->apiKey,
            'search' => $query,
            'page_size' => $pageSize,
            'count' => 10000
        ];

        $url = $this->apiBase . '?' . http_build_query($params);
        $response = @file_get_contents($url);

        if (!$response) return [];
        $data = json_decode($response, true);

        return $data['results'] ?? [];
    }

    public function getDescription(int $id): string
    {
        $url = "{$this->apiBase}/{$id}?key={$this->apiKey}";
        $response = @file_get_contents($url);
        if (!$response) return "No description available";
        $data = json_decode($response, true);
        return $data['description'] ?? "No description available";
    }

    public function getTrailers(int $id): array
    {
        $url = "{$this->apiBase}/{$id}/movies?key={$this->apiKey}";
        $response = @file_get_contents($url);
        if (!$response) return [];
        $data = json_decode($response, true);
        if (!isset($data['results'])) return [];
        return array_map(fn($movie) => $movie['data']['max'] ?? null, $data['results']);
    }
}
