<?php

namespace modules\game\controllers;

use modules\game\models\Game;
use modules\game\models\Gallery;

class GameController
{
  private $model;

  public function __construct()
  {
    $this->model = new Game();
  }


  /*
   * Individual Game Page
   */
  public function show($id){
    $game = $this->model->getGame($id);
    if(empty($game)){
      http_response_code(404);
      echo "Game not found";
      exit;
    }
    ob_start();
    require __DIR__ . '/../views/single_game.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }
  /*
   * Games page
   */
  public function index($page = 1): array
  {
    $data = $this->model->getGames($page);

    $games = [];

    $model = new Gallery();
    $images = $model->getGallery();

    foreach ($data['results'] as $item) {

      $id = $item['id'];

      $item['description'] =
        $this->model->getDescription($id);

      $item['trailers'] =
        $this->model->getTrailers($id);

      $item['short_screenshots'] =
        $this->model->getScreenshots($id);

      $games[] = $item;
    }


    ob_start();

    require __DIR__ . "/../views/index.php";

    $content = ob_get_clean();

    require __DIR__ . "/../../../app/views/layout.php";


    return $games;
  }


  /*
   * Load more games
   *
   */
  public function loadMore($page = 1)
  {
    var_dump($page);
    exit;
    $data = $this->model->getGames($page);

    $games = $data['results'] ?? [];


    header('Content-Type: application/json');

    echo json_encode($games);

    exit;
  }
}
