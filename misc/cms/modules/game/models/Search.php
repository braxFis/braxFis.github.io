<?php

namespace modules\game\models;

use app\models\RAWG_API;
use app\models\SPOTIFY_API;
use app\models\TMDB_API;
use modules\music\models\Music;

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

  public function findAlbums(string $query): array
  {
    $music = new Music();

    $data = $music->search($query, 'album');

    return $data['albums']['items'] ?? [];
  }

  public function findAlbumsByYear(int $year): array
  {
    $music = new Music();

    $albums = [];

    for ($page = 0; $page < 10; $page++) {

      $offset = $page * 10;

      $data = $music->search(
        'year:' . $year,
        'album',
        $offset
      );

      $items = $data['albums']['items'] ?? [];

      if (empty($items)) {
        break;
      }

      $albums = array_merge($albums, $items);

      if (count($items) < 10) {
        break;
      }
    }

    usort($albums, function ($a, $b) {

      return strcmp(
        $b['release_date'] ?? '',
        $a['release_date'] ?? ''
      );

    });

    return $albums;
  }

  public function findPeople(string $query): array
  {
    $tmdb = new TMDB_API();

    return $tmdb->searchPeople($query);
  }
}
