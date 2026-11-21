<?php

namespace modules\genre\models;

use app\services\GameDataService;

class Genre {

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
    return (new GameDataService())->fetch($endpoint, $params);
  }

  public function getGames($slug):array{
    if (!isset($this->genres[$slug])) {
      return [];
    }
    $genreId = $this->genres[$slug];

    $params = [
      'page' => 1,
      'page_size' => 10,
      'genres' => $genreId,
      // Internal hint: lets IGDB resolve the equivalent without reusing a RAWG ID.
      'genre_slug' => $slug
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
