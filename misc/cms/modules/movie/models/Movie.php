<?php

namespace modules\movie\models;

use app\models\TMDB_API;

class Movie extends TMDB_API {

    private function fetchAPI($endpoint, $params = []) {
        $params['api_key'] = (new TMDB_API)->apiKey;
        $url = (new TMDB_API)->baseUrl . $endpoint . '?' . http_build_query($params);

        $response = file_get_contents($url);
        if (!$response) return null;
        return json_decode($response, true);
    }

    public function getMovie($id):?array{
      $data = $this->fetchAPI("movie/{$id}");
      if(!$data) return null;
      return $data;
    }

  public function getMovies($page = 1)
  {
    $params = [
    ];

    return $this->fetchAPI('discover/movie', $params);
  }

    public function getDescription($id) {
        $data = $this->fetchAPI("movie/{$id}");
        return $data['description'] ?? 'No description available';
    }

    public function getTrailers($id): array
    {
      $data = $this->fetchAPI("movie/{$id}/videos");
      $trailers = [];
      foreach ($data['results'] ?? [] as $trailer) {
        if (
          $trailer['site'] === 'Youtube' &&
          $trailer['type'] === 'Trailer'
        ) {
          $trailers[] = $trailer;
        }
      }
      return $trailers;
    }

    public function getScreenshots($id): array{
        $data = $this->fetchAPI("movie/{$id}/images");
        if (!$data || !isset($data["results"])) return [];
        return array_map(function ($r) {
        }, $data["results"]);
    }

    public function getCertification($id, $country = "US"){
      $data = $this->fetchAPI("movie/{$id}/release_dates");
      foreach($data['results'] ?? [] as $result){
        if($result['iso_3166_1'] === $country){
          return $result['release_dates'][0]['certification'] ?? null;
        }
      }
      return null;
    }

    public function getGenres($movie){
      $movie = $this->fetchAPI("movie/{$movie['id']}");
      return $movie['genres'] ?? [];
    }

    public function getDetails($movie){
      return $this->fetchAPI("movie/{$movie['id']}");
    }
}
