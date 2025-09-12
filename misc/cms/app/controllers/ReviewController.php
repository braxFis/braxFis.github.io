<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Review.php';

class ReviewController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Review;
  }

  public function index() {
    $reviews = $this->model->getReviews();
    require __DIR__ . '/../views/reviews/index.php';
  }

  public function create(){
    $reviewModel = new \app\models\Review;
    $reviews = $reviewModel->getReviews();
    //$menus
    //$footers
    //ob_start();
    require __DIR__ . '/../views/reviews/create.php';
    //$content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
  }

  public function store($postData){
    $this->model->create($postData);
    header('Location: /reviews');
  }

  public function edit($id){
    $review = $this->model->getReview($id);
    if($review == null){
      require __DIR__ . '/app/views/errors/404.php';
      exit;
    }
    require __DIR__ . '/../views/reviews/edit.php';
  }

  public function update($id, $data){
    $this->model->update($id, $data);
    header('Location: /reviews');
    exit;
  }

  public function delete($id){
    $this->model->delete($id);
    header('Location: /reviews');
    exit;
  }

}
