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
    $games = $this->model->getGames();

    $top10 = $this->model->getTop10();

    $upcoming = $this->model->getUpcoming();


    ob_start();

    require __DIR__ . "/../views/index.php";

    $content = ob_get_clean();

    require __DIR__ . "/../../../app/views/layout.php";


    return $games;
  }
}
