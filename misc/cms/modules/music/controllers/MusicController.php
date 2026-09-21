<?php

namespace modules\music\controllers;

use modules\music\models\Music;

class MusicController
{
  private $model;

  public function __construct()
  {
    $this->model = new Music();
  }

/*
* Music Albums... Main Page
*/
  public function index()
  {
    $data = $this->model->search('music', 'album');

    $artists = $data['albums']['items'] ?? [];

    ob_start();

    require __DIR__ . '/../views/index.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }

  /*
* Artists... Main Page
*/
  public function indexArtists()
  {
    $data = $this->model->search('music', 'artist');

    $artists = $data['artists']['items'] ?? [];

    ob_start();

    require __DIR__ . '/../views/artists.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }

  /*
   * Individual Album Page
   */
  public function show($id){
    $album = $this->model->getAlbum($id);
    if(empty($album)){
      http_response_code(404);
      echo "Album not found";
      exit;
    }
    ob_start();
    require __DIR__ . '/../views/single_album.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }

  /*
   * Individual Artist Page
   */
  public function artist($id){
    $artist = $this->model->getArtist($id);
    $genres = $artist['genres'] ?? [];
    $albums = $this->model->getArtistAlbums($id);
    if(empty($artist)){
      http_response_code(404);
      echo "Artist not found";
      exit;
    }
    ob_start();
    require __DIR__ . '/../views/single_artist.php';
    $content = ob_get_clean();
    require __DIR__ . '/../../../app/views/layout.php';
  }

  /*
   * Albums page
   */
  public function album($id): array
  {
    $data = $this->model->getArtistAlbums($id);

    $albums = $data['items'] ?? [];

    ob_start();

    require __DIR__ . "/../views/albums.php";

    $content = ob_get_clean();

    require __DIR__ . "/../../../app/views/layout.php";

    return $albums;
  }
}
