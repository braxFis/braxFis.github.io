<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;
use app\models\Regular;
use modules\game\controllers\GameController;
use modules\game\models\Game;

require_once __DIR__ . '/../models/Regular.php';

class RegularController extends BaseController{
  private $model;

  public function __construct(){
    $this->model = new Regular;
  }

  public function index(){
    //Include Game MVC
    $games = (new GameController())->index();
  }
}
