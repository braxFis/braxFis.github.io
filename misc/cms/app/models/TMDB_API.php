<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class TMDB_API{

    protected string $apiKey = "891feba4d2bc4be6a725134b1c38e4c3";
    protected string $baseUrl = "http://api.themoviedb.org/3/";

    //When understanding what is happening under the hood
    //Start adding more functions from here
  public function searchMovies(string $query): array{
    if (empty(trim($query))) {
      return [];
    }

    $params = [
      'api_key' => $this->apiKey,
      'query' => trim($query)
    ];

    $url = $this->baseUrl . "search/movie?" . http_build_query($params);

    $response = @file_get_contents($url);

    if (!$response) {
      return [];
    }

    $data = json_decode($response, true);

    return $data['results'] ?? [];
  }

  public function searchPeople(string $query): array
  {
    if (empty(trim($query))) {
      return [];
    }

    $params = [
      'api_key' => $this->apiKey,
      'query'   => trim($query)
    ];

    $url = $this->baseUrl . "search/person?" . http_build_query($params);

    $response = @file_get_contents($url);

    if (!$response) {
      return [];
    }

    $data = json_decode($response, true);

    return $data['results'] ?? [];
  }

}
