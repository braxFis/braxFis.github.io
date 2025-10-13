<?php
namespace modules\game\models;

class Sidebar
{
    private $apiKey = "8bc47dab600645ac9164f534d0182baf";
    private $apiBase = "https://api.rawg.io/api/games";

    public function getGames(string $ordering = "-rating", string $dates = "2026-01-01", int $page = 1, int $limit = 10): array
    {
        $params = [
            'key' => $this->apiKey,
            'page' => $page,
            'dates' => $dates,
            'ordering' => $ordering
        ];

        $url = $this->apiBase . '?' . http_build_query($params);
        $response = @file_get_contents($url);
        if (!$response) return [];

        $data = json_decode($response, true);
        return $data['results'] ?? [];
    }

    public function getTopMetacritic(int $limit = 10): array
    {
        $games = $this->getGames('-metacritic');
        usort($games, fn($a, $b) => ($b['metacritic'] ?? 0) <=> ($a['metacritic'] ?? 0));
        return array_slice($games, 0, $limit);
    }
}
