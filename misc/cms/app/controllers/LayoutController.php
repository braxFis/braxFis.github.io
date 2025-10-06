<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Layout.php';

class LayoutController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Layout;
  }

  public function index(){
    $layout = $this->model->getLayout();
    include __DIR__ . '/../../public/testDnD.php';
  }

  public function save(){
    $data = json_decode(file_get_contents("php://input"), true);
    $this->model->saveLayout($data);
    echo json_encode(['status' => 'success']);
  }
}
