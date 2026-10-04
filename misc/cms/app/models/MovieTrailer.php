<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class MovieTrailer extends TMDB_API {
    public function __construct(){
    }

    private function fetchAPI($endpoint, $params = []) {
        $params['api_key'] = (new TMDB_API())->apiKey;
        $url = (new TMDB_API)->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

    public function getMovieTrailers($id): array{
    $data = $this->fetchAPI("movie/{$id}/videos");
    if (!$data || !isset($data['results'])) return [];

    return array_map(function ($r) {
        return [
            'name'    => $r['name'] ?? '',
            'size'    => $r['size'] ?? '',
            'key'     => $r['key'] ?? ''
        ];
    }, $data['results']);
    }

}
