<?php
namespace modules\game\models;

class Trailer {
    private $apiKey = "8bc47dab600645ac9164f534d0182baf";
    private $baseUrl = "https://api.rawg.io/api";

    public function getGamesPage($page = 1, $pageSize = 40) {
        $url = "{$this->baseUrl}/games?key={$this->apiKey}&page={$page}&page_size={$pageSize}";
        $json = @file_get_contents($url);
        if (!$json) return [];
        $data = json_decode($json, true);
        return $data['results'] ?? [];
    }

    public function getGameTrailers($gameId) {
        $url = "{$this->baseUrl}/games/{$gameId}/movies?key={$this->apiKey}";
        $json = @file_get_contents($url);
        if (!$json) return [];
        $data = json_decode($json, true);
        return $data['results'] ?? [];
    }
}
