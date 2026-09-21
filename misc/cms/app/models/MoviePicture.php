<?php

namespace app\models;

use Database;
use app\models\TMDB_API;

require_once __DIR__ . '/../../bootstrap.php';

class MoviePicture extends TMDB_API {
    private $db;

    public function __construct(){
        $this->db = new \Database;
    }

    private function fetchAPI($endpoint, $params = []) {
        $params['api_key'] = (new TMDB_API)->apiKey;
        $url = (new TMDB_API)->baseUrl . $endpoint . '?' . http_build_query($params);
        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

  public function getMovieScreenshots($id): array
  {
    $data = $this->fetchAPI("movie/{$id}/images");

    if (!$data || empty($data['backdrops'])) {
      return [];
    }

    $screenshots = [];

    foreach ($data['backdrops'] as $image) {

      if (empty($image['file_path'])) {
        continue;
      }

      $screenshots[] = [
        'file_path' => 'https://image.tmdb.org/t/p/w780' . $image['file_path']
      ];
    }

    return $screenshots;
  }
}
