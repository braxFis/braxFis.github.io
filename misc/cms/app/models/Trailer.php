<?php

namespace app\models;

use app\services\GameDataService;

require_once __DIR__ . '/../../bootstrap.php';

class Trailer{

    public function __construct(){
    }

    private function fetchAPI($endpoint, $params = []) {
      return (new GameDataService())->fetch($endpoint, $params);
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
