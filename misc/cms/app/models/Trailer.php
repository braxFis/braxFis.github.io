<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Trailer extends RAWG_API{
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    private function fetchAPI($endpoint, $params = []) {
        $params['key'] = (new RAWG_API)->apiKey;
        $url = (new RAWG_API)->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

    public function getTrailers($id): array{
    $data = $this->fetchAPI("games/{$id}/movies");
    if (!$data || !isset($data['results'])) return [];

    return array_map(function ($r) {
        return [
            'name'    => $r['name'] ?? '',
            'preview' => $r['preview'] ?? '',
            'max'     => $r['data']['max'] ?? '',
        ];
    }, $data['results']);
    }

}