<?php

namespace modules\home\models;

use app\models\RAWG_API;

class Home extends RAWG_API
{
private function fetchAPI($endpoint, $params = [])
{
    $params['key'] = $this->apiKey;

    $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        error_log("RAWG request failed: {$url}");
        return ['results' => []];
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        error_log("RAWG returned invalid JSON: {$url}");
        return ['results' => []];
    }

    return $data;
}


  /*
  |--------------------------------------------------------------------------
  | Main Content
  |--------------------------------------------------------------------------
  */

  public function getGames(): array
  {
    $today = date('Y-m-d');
    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));

    $params = [
      'page'      => 1,
      'page_size' => 20,
      'dates'     => $oneMonthAgo . ',' . $today,
      'ordering'  => '-released'
    ];

    $data = $this->fetchAPI('games', $params);

    return $data['results'] ?? [];
  }

  /*
|--------------------------------------------------------------------------
| Trailer Games
|--------------------------------------------------------------------------
*/

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

      if (count($trailerGames) >= 6) {
        break;
      }
    }

    return $trailerGames;
  }
  /*
  |--------------------------------------------------------------------------
  | Top 10
  |--------------------------------------------------------------------------
  */

  public function getTop10(): array
  {
    $params = [
      'page'      => 1,
      'page_size' => 10,
      'ordering'  => '-rating'
    ];

    $data = $this->fetchAPI('games', $params);

    return $data['results'] ?? [];
  }


  /*
  |--------------------------------------------------------------------------
  | Upcoming
  |--------------------------------------------------------------------------
  */

  public function getUpcoming(): array
  {
    $today = date('Y-m-d');
    $oneYearFromNow = date('Y-m-d', strtotime('+1 year'));

    $params = [
      'page'      => 1,
      'page_size' => 10,
      'dates'     => $today . ',' . $oneYearFromNow,
      'ordering'  => '-added'
    ];

    $data = $this->fetchAPI('games', $params);

    return $data['results'] ?? [];
  }

}
