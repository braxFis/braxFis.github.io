<?php

namespace modules\publisher\models;

use app\models\RAWG_API;

class Publisher extends RAWG_API {

  public array $publishers = [
    'electronic-arts' => 354,
    'square-enix' => 308,
    'ubisoft-entertainment' => 918,
    'microsoft-studios' => 20987,
    'sega-2' => 3408,
    'valve' => 3399,
    'bethesda-softworks' => 339,
    '2k-games' => 358,
    'feral-interactive' => 19651,
    'capcom' => 2150
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
    $platformId = $this->publishers[$slug];

    $params = [
      'page' => 1,
      'page_size' => 10,
      'publishers' => $platformId
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
