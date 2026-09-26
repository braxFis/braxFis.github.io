<?php

namespace app\models;

use Database;

require_once __DIR__ . '/../../bootstrap.php';

class SPOTIFY_API{

    protected string $baseUrl = "https://api.spotify.com/v1/";
    private $clientId = "2febd08e0bed426da83f6d094e7a8de3";
    private $clientSecret = "aa7f7774f25b422a98b7d0fe8d15e3e8";

    //When understanding what is happening under the hood
    //Start adding more functions from here
  public function getAccessToken()
  {
    $ch = curl_init("https://accounts.spotify.com/api/token");

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: Basic " . base64_encode(
        $this->clientId . ":" . $this->clientSecret
      ),
      "Content-Type: application/x-www-form-urlencoded"
    ]);

    curl_setopt(
      $ch,
      CURLOPT_POSTFIELDS,
      "grant_type=client_credentials"
    );

    $response = curl_exec($ch);

    if ($response === false) {
      die(
        "Spotify CURL error: " .
        curl_error($ch)
      );
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode !== 200) {
      echo "<pre>";
      print_r($data);
      echo "</pre>";
      die("Spotify token request failed. HTTP: " . $httpCode);
    }

    return $data['access_token'] ?? null;
  }

}
