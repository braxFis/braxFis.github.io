<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Command.php';

class CommandController extends BaseController{
  private $model;

  public function __construct(){
    $this->model = new \app\models\Command;
  }

  /*
   * Bestäm vilka kommando du vill köra?
   *
   * */
}
