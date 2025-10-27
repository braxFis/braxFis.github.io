<?php

namespace modules\game\models;

use app\models\RAWG_API;

class Game extends RAWG_API {

    private function fetchAPI($endpoint, $params = []) {
        $params['key'] = (new RAWG_API)->apiKey;
        $url = (new RAWG_API)->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

    public function getGames($page = 1) {
        $params = [
            'page' => $page,
            'page_size' => 1
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

    public function getScreenshots($id): array{
        $data = $this->fetchAPI("games/{$id}/screenshots");
        if (!$data || !isset($data["results"])) return [];
        return array_map(function ($r) {
            var_dump($r);
        }, $data["results"]);
    }
}
