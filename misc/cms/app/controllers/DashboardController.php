<?php

namespace app\controllers;

require_once __DIR__ . "/../models/Dashboard.php";

class DashboardController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Dashboard;
  }

  public function index() {
    //Decide how the layout of the Dashboard should be
    ob_start();
    require __DIR__ . "/../views/dashboard/index.php";
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }
}

?>
