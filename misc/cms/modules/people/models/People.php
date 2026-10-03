<?php

namespace modules\people\models;

use app\models\TMDB_API;

class People extends TMDB_API
{
  private function fetchAPI($endpoint, $params = [])
  {
    $params['api_key'] = (new TMDB_API)->apiKey;

    $url =
      (new TMDB_API)->baseUrl .
      $endpoint .
      '?' .
      http_build_query($params);

    $response = file_get_contents($url);

    if (!$response) {
      return null;
    }

    return json_decode($response, true);
  }


  /*
   * People page
   */
  public function getPeople(): array
  {
    $data = $this->fetchAPI('person/popular');

    if (!$data) {
      return [];
    }

    return $data['results'] ?? [];
  }


  /*
   * Individual Person
   */
  public function getPerson($id): ?array
  {
    $data = $this->fetchAPI("person/{$id}");

    if (!$data) {
      return null;
    }

    return $data;
  }


  /*
   * Person's Movie Credits
   */
  public function getMovieCredits($id): array
  {
    $data = $this->fetchAPI(
      "person/{$id}/movie_credits"
    );

    if (!$data) {
      return [];
    }

    return $data['cast'] ?? [];
  }
}
