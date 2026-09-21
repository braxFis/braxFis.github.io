<?php

namespace modules\genre\models;

use app\models\RAWG_API;

class Genre extends RAWG_API {

  public array $genres = [
    'action' => 4,
    'indie' => 51,
    'adventure' => 3,
    'role-playing-games-rpg' => 5,
    'strategy' => 10,
    'shooter' => 2,
    'casual' => 40,
    'simulation' => 14,
    'puzzle' => 7,
    'arcade' => 11,
    'platformer' => 83,
    'massively-multiplayer' => 59,
    'racing' => 1,
    'sports' => 15,
    'fighting' => 6,
    'family' => 19,
    'board-games' => 28,
    'card' => 17,
    'educational' => 34
  ];

  private function fetchAPI($endpoint, $params = []){
    $params['key'] = $this->apiKey;
    $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);
    $response = file_get_contents($url);
    if(!$response){
      return null;
    }
    return json_decode($response, true);
  }

  public function getGames($slug):array{
    $platformId = $this->genres[$slug];

    $params = [
      'page' => 1,
      'page_size' => 10,
      'genres' => $platformId
    ];

    $data = $this->fetchAPI('games', $params);

    return $data['results'] ?? [];
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
