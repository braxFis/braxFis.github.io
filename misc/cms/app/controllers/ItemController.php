<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Item.php';

use app\controllers\BaseController;

class ItemController extends BaseController {

  private $model;
  public function __construct() {
    $this->model = new \app\models\Item;
  }

  public function index() {
    $data = json_decode(file_get_contents("php://input"), true);
    $items = $this->model->getItems();
    $itemModel = new \app\models\Item;
    //foreach ($data as $row) {
    //  $itemModel->updatePosition($row['id'], $row['position']);
    //}

    echo json_encode(["status" => "success"]);
  }
}
