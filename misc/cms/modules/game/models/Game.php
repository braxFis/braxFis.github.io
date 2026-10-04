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

  public function getGame($id):?array{
      $data = $this->fetchAPI("games/{$id}");
      if(!$data) return null;
      return $data;
    }

  public function getGames($page = 1)
  {
    $params = [
      'page'      => max(1, (int)$page),
      'page_size' => 20,
      'dates' => '2026-07-19,2026-12-30',
      'ordering'  => '-released'
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

    if (!$data || !isset($data['results'])) {
      return [];
    }

    return array_map(function ($r) {

      return [
        'name'    => $r['name'] ?? 'Trailer',
        'preview' => $r['preview'] ?? '',
        'max'     => $r['data']['max'] ?? ''
      ];

    }, $data['results']);
  }

  public function getScreenshots($id): array{
        $data = $this->fetchAPI("games/{$id}/screenshots");
        if (!$data || !isset($data["results"])) return [];
        return array_map(function ($r) {
        }, $data["results"]);
    }

  public function getTrailerGames(): array
  {
    $params = [
      'page'      => 1,
      'page_size' => 30,
      'ordering'  => '-added'
    ];

    $data = $this->fetchAPI('games', $params);

    $games = $data['results'] ?? [];

    $trailerGames = [];

    foreach ($games as $game) {

      if (empty($game['id'])) {
        continue;
      }

      $trailerData = $this->fetchAPI(
        "games/{$game['id']}/movies"
      );

      if (empty($trailerData['results'])) {
        continue;
      }

      $trailerGames[] = $game;

      if (count($trailerGames) >= 12) {
        break;
      }
    }

    return $trailerGames;
  }
}
