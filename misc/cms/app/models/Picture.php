<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class Picture{
    private $db;
    private $apiKey = "8bc47dab600645ac9164f534d0182baf";
    private $baseUrl = "https://api.rawg.io/api/";

    public function __construct(){
        $this->db = new \Database;
    }

    private function fetchAPI($endpoint, $params = []) {
        $params['key'] = $this->apiKey;
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

public function getScreenshots($id, $page = 1): array {
    // rätt endpoint
    $data = $this->fetchAPI("games/{$id}/screenshots", ['page' => $page]);

    // screenshots ligger i 'results'
    if (!$data || !isset($data["results"])) return [];

    // mappa ut bara bild-URL:er
    return array_map(function ($r) {
        return [
            "image" => $r["image"] ?? null
        ];
    }, $data["results"]);
}

}