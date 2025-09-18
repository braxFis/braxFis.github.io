<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Review.php';

class ReviewController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Review;
  }

  public function index() {
    $reviews = $this->model->getReviews();
    ob_start();
    require __DIR__ . '/../views/reviews/index.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function create(){
    $this->requireAdmin();
    $reviewModel = new \app\models\Review;
    $reviews = $reviewModel->getReviews();
    ob_start();
    require __DIR__ . '/../views/reviews/create.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function store($postData){
    $this->requireAdmin();
    $this->model->create($postData);
    header('Location: /review');
  }

  public function edit($id){
    $review = $this->model->getReview($id);
    if($review == null){
      //require __DIR__ . '/app/views/errors/404.php';
      exit;
    }
    ob_start();
    require __DIR__ . '/../views/reviews/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    header('Location: /review');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /review');
    exit;
  }

}
