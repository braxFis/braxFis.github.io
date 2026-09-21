<?php

namespace modules\game\models;

use app\models\RAWG_API;
use app\models\TMDB_API;

class Search extends RAWG_API
{
  public TMDB_API $tmdb;

  public function findGames(string $query, int $pageSize = 10): array
  {
    if (empty(trim($query))) {
      return [];
    }

    $params = [
      'key'       => $this->apiKey,
      'search'    => trim($query),
      'page_size' => $pageSize
    ];

    $url = $this->baseUrl . 'games?' . http_build_query($params);

    $response = @file_get_contents($url);

    if (!$response) {
      return [];
    }

    $data = json_decode($response, true);

    return $data['results'] ?? [];
  }

  public function findMovies(string $query): array{
    $tmdb = new TMDB_API();
    return $tmdb->searchMovies($query);
  }

}
