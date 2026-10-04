<?php

namespace modules\home\controllers;

use modules\home\models\Home;

class HomeController
{
  private $model;

  public function __construct()
  {
    $this->model = new Home();
  }


  public function index(): array
  {
    /*
     * Homepage games
     */
    $games = $this->model->getGames();


    /*
     * Games with trailers
     */
    $trailerGames = $this->model->getTrailerGames();


    /*
     * Top 10 games
     */
    $top10 = $this->model->getTop10();


    /*
     * Upcoming games
     */
    $upcoming = $this->model->getUpcoming();


    /*
     * Gallery Rotator
     *
     * Convert the game data into the format
     * expected by GalleryWidget.
     */
    $galleryItems = [];

    foreach ($games as $game) {

      if (
        empty($game['id']) ||
        empty($game['background_image'])
      ) {
        continue;
      }


      $galleryItems[] = [
        'id'       => (int) $game['id'],
        'image'    => $game['background_image'],
        'title'    => $game['name'] ?? 'Unknown Game',
        'subtitle' => 'Featured Game',
        'url'      => '/games/' . (int) $game['id']
      ];


      /*
       * The rotator currently uses
       * six items.
       */
      if (count($galleryItems) >= 6) {
        break;
      }
    }


    /*
     * Render homepage view.
     */
    ob_start();

    require __DIR__ . "/../views/index.php";

    $content = ob_get_clean();


    /*
     * Render global layout.
     *
     * $galleryItems is now available
     * to layout.php.
     */
    require __DIR__ . "/../../../app/views/layout.php";


    return $games;
  }
}
