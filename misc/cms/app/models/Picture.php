<?php

namespace app\models;

use app\services\GameDataService;

class Picture
{
  private function fetchAPI($endpoint, $params = [])
  {
    return (new GameDataService())->fetch($endpoint, $params);
  }

  public function getScreenshots($id, $page = 1): array
  {
    $data = $this->fetchAPI(
      "games/{$id}/screenshots",
      ['page' => $page]
    );

    if (!$data || !isset($data['results'])) {
      return [];
    }

    return array_map(function ($r) {
      return [
        'image' => $r['image'] ?? null
      ];
    }, $data['results']);
  }
}
