<?php

namespace app\controllers;

use app\models\Component;

class ComponentController {

  private $model;

  public function __construct() {
    $this->model = new \app\models\Component;
  }
  public function move() {
    $id = $_POST['id'];
    $newController = $_POST['target'];
    $this->model->move($id, $newController);
    echo json_encode(['status' => 'success']);
  }
}
