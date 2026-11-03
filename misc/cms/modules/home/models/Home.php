<?php

namespace modules\home\models;

use app\models\RAWG_API;

class Home extends RAWG_API
{
private function fetchAPI($endpoint, $params = [])
{
    $params['key'] = $this->apiKey;

    $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_USERAGENT => 'BraxFisCMS/1.0'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        error_log("RAWG request failed: {$url}");
        return ['results' => []];
    }

    return json_decode($response, true) ?: ['results' => []];
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
