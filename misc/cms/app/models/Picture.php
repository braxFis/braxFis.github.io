<?php

namespace app\models;

use app\models\RAWG_API;

class Picture extends RAWG_API
{
  private function fetchAPI($endpoint, $params = [])
  {
    $params['key'] = $this->apiKey;

    $url =
      $this->baseUrl .
      $endpoint .
      '?' .
      http_build_query($params);

    $response = file_get_contents($url);

    if (!$response) {
      return null;
    }

    return json_decode($response, true);
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
