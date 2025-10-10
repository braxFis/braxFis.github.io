<?php

namespace app\controllers;

use app\models\Like;

require_once __DIR__ . '/../models/Like.php';

class LikeController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new \app\models\Like;
  }

  public function displayLike(){
    require_once __DIR__ . '/../views/like/like.html';
  }

  public function displayNote(){
    require_once __DIR__ . '/../views/like/notes.html';
  }

  public function displayWishlist(){
    require_once __DIR__ . '/../views/like/wishlist.html';
  }

  public function displayTodo(){
    require_once __DIR__ . '/../views/like/todo.html';
  }

  public function displayShare(){
    require_once __DIR__ . '/../views/like/share.html';
  }
}
