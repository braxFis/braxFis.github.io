<?php

namespace modules\music\models;

use app\models\SPOTIFY_API;

class Music extends SPOTIFY_API
{
  private $accessToken;

  public function __construct()
  {
    $this->accessToken = $this->getAccessToken();

    if (!$this->accessToken) {
      die('Spotify access token could not be retrieved.');
    }
  }

  public function fetchAPI($endpoint, $params = [])
  {
    $url = $this->baseUrl . $endpoint;

    if (!empty($params)) {
      $url .= '?' . http_build_query($params);
    }

    $context = stream_context_create([
      'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer " . $this->accessToken
      ]
    ]);

    $response = file_get_contents($url, false, $context);

    if (!$response) {
      return null;
    }

    return json_decode($response, true);
  }

  public function search($query, $type = 'artist', $offset = 0)
  {
    return $this->fetchAPI(
      'search',
      [
        'q' => $query,
        'type' => $type,
        'limit' => 10,
        'offset' => $offset
      ]
    );
  }

  public function searchAlbumsByYear($year, $offset = 0)
  {
    return $this->search(
      'year:' . $year,
      'album',
      $offset
    );
  }

  public function searchNewAlbums($offset = 0)
  {
    return $this->search(
      'tag:new',
      'album',
      $offset
    );
  }
  public function getArtist($id)
  {
    return $this->fetchAPI(
      'artists/' . $id
    );
  }

  public function getArtistAlbums($id)
  {
    return $this->fetchAPI(
      'artists/' . $id . '/albums',
      [
        'limit' => 10
      ]
    );
  }

  public function getAlbum($id)
  {
    return $this->fetchAPI(
      'albums/' . $id
    );
  }

  public function getTrack($id)
  {
    return $this->fetchAPI(
      'tracks/' . $id
    );
  }
}
