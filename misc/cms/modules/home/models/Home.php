<?php

namespace modules\home\models;

use app\models\RAWG_API;

class Home extends RAWG_API
{
  private function fetchAPI($endpoint, $params = [])
  {
    $params['key'] = $this->apiKey;

    $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

    $response = file_get_contents($url);

    if (!$response) {
      return null;
    }

    return json_decode($response, true);
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
      'page_size' => 10,
      'dates'     => $oneMonthAgo . ',' . $today,
      'ordering'  => '-released'
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
