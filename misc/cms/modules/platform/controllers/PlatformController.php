<?php

namespace modules\platform\controllers;

use modules\platform\models\Platform;

class PlatformController
{
  private $model;

  public function __construct()
  {
    $this->model = new Platform();
  }


  public function index($slug): array
  {
    /*
     * Platform games
     */
    $games = $this->model->getGames($slug);

    /*
     * Sidebar data
     */
    $top10 = $this->model->getTop10();
    $upcoming = $this->model->getUpcoming();


    /*
     * Gallery Rotator
     *
     * Use the games belonging to
     * the current platform.
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
        'subtitle' => strtoupper($slug),
        'url'      => '/games/' . (int) $game['id']
      ];


      /*
       * GalleryWidget currently displays
       * six items.
       */
      if (count($galleryItems) >= 6) {
        break;
      }
    }


    /*
     * Render platform view.
     */
    ob_start();

    require __DIR__ . "/../views/index.php";

    $content = ob_get_clean();


    /*
     * Render global layout.
     *
     * $galleryItems is available
     * to layout.php.
     */
    require __DIR__ . "/../../../app/views/layout.php";


    return $games;
  }
}
