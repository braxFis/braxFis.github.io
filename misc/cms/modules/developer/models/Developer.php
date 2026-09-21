<?php

namespace modules\developer\models;

use app\models\RAWG_API;

class Developer extends RAWG_API {

  public array $developers = [
    'ubisoft' => 405,
    'valve-software' => 1612,
    'feral-interactive' => 18893,
    'ubisoft-montreal' => 3709,
    'square-enix' => 4132,
    'capcom' => 3678,
    'electronic-arts' => 109,
    'aspyr-media' => 17132,
    'sony-interactive-entertainment' => 6,
    'sega' => 425
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
    $platformId = $this->developers[$slug];

    $params = [
      'page' => 1,
      'page_size' => 10,
      'developers' => $platformId
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
