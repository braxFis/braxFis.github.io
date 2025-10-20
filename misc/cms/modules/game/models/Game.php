<?php

namespace modules\game\models;

class Game {
    private $apiKey = "8bc47dab600645ac9164f534d0182baf";
    private $baseUrl = "https://api.rawg.io/api/";

    private function fetchAPI($endpoint, $params = []) {
        $params['key'] = $this->apiKey;
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

    public function getGames($page = 1) {
        $params = [
            'page' => $page,
            'page_size' => 1,
            'dates' => '2025-01-01'
        ];
        return $this->fetchAPI('games', $params);
    }

    public function getDescription($id) {
        $data = $this->fetchAPI("games/{$id}");
        return $data['description'] ?? 'No description available';
    }

    public function getTrailers($id): array
    {
        $data = $this->fetchAPI("games/{$id}/movies");
        if (!$data || !isset($data['results'])) return [];
        return array_map(function ($r) {
          return $r['data']['max'];
        }, $data['results']);
    }
}
