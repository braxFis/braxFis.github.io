<?php
namespace modules\game\models;

use app\models\RAWG_API;

class Search extends RAWG_API
{

    public function findGames(string $query, int $pageSize = 10): array
    {
        if (empty($query)) return [];

        $params = [
            'key' => (new RAWG_API)->apiKey,
            'search' => $query,
            'page_size' => $pageSize,
            'count' => 10000
        ];

        $url = (new RAWG_API)->baseUrl . '?' . http_build_query($params);
        $response = @file_get_contents($url);

        if (!$response) return [];
        $data = json_decode($response, true);

        return $data['results'] ?? [];
    }

    public function getDescription(int $id): string
    {
        $url = (new RAWG_API)->baseUrl . "/{$id}?key=" .  (new RAWG_API)->apiKey;
        $response = @file_get_contents($url);
        if (!$response) return "No description available";
        $data = json_decode($response, true);
        return $data['description'] ?? "No description available";
    }

    public function getTrailers(int $id): array
    {
        $url = (new RAWG_API)->baseUrl . "/{$id}/movies?key=" . (new RAWG_API)->apiKey;
        $response = @file_get_contents($url);
        if (!$response) return [];
        $data = json_decode($response, true);
        if (!isset($data['results'])) return [];
        return array_map(fn($movie) => $movie['data']['max'] ?? null, $data['results']);
    }
}
