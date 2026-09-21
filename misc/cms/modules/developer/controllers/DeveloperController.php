<?php

namespace modules\developer\controllers;

use modules\developer\models\Developer;

class DeveloperController{
  private $model;

  public function __construct() {
    $this->model = new Developer();
  }

  public function index($slug): array
  {
    $games = $this->model->getGames($slug);
    $top10 = $this->model->getTop10();
    $upcoming = $this->model->getUpcoming();

    ob_start();

    require __DIR__ . "/../views/index.php";

    $content = ob_get_clean();

    require __DIR__ . "/../../../app/views/layout.php";

    return $games;
  }
}
