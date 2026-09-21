<?php

namespace modules\platform\models;

use app\models\RAWG_API;

class Platform extends RAWG_API {

  public array $platforms = [
    'ps5' => 187,
    'ps4' => 18,
    'ps3' => 16,
    'pc' => 4,
    'xbox-one' => 1,
    'xbox-series-x' => 186,
    'xbox360' => 14,
    'nintendo-switch' => 7,
    'macos' => 5
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
    $platformId = $this->platforms[$slug];

    $params = [
      'page' => 1,
      'page_size' => 10,
      'platforms' => $platformId
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
