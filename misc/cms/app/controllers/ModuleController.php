<?php
namespace app\controllers;

use app\models\Module;

class ModuleController {
  private $model;

  public function __construct() {
    $this->model = new Module();
  }

  public function move() {
    $data = json_decode(file_get_contents('php://input'), true);
    $this->model->moveModule($data['id'], $data['to'], $data['position']);
    echo json_encode(['status' => 'success']);
  }
}
