<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Preview.php';

class PreviewController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Preview;
  }

  public function index() {
    $reviews = $this->model->getPreviews();
    require __DIR__ . '/../views/previews/index.php';
  }

  public function create(){
    $this->requireAdmin();
    $reviewModel = new \app\models\Preview;
    $reviews = $reviewModel->getPreviews();
    //$menus
    //$footers
    //ob_start();
    require __DIR__ . '/../views/previews/create.php';
    //$content = ob_get_clean();
    //require __DIR__ . '/../views/layout.php';
  }

  public function store($postData){
    $this->requireAdmin();
    $this->model->create($postData);
    header('Location: /preview');
  }

  public function edit($id){
    $review = $this->model->getPreview($id);
    if($review == null){
      //require __DIR__ . '/app/views/errors/404.php';
      exit;
    }
    require __DIR__ . '/../views/previews/edit.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    header('Location: /preview');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /preview');
    exit;
  }

}
