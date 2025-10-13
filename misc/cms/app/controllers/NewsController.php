<?php

namespace app\controllers;

require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/Comment.php';

class NewsController extends BaseController {
  private $model;
  private $media;
  public function __construct() {
    $this->model = new \app\models\News;
    $this->media = new \app\models\Media;
  }

  public function indieNews($id){
    $new = $this->model->getNew($id);
    if($new == null){
      require __DIR__ . '/../views/404.php';
      return;
    }
    $commentModel = new \app\models\Comment;
    $comments = $commentModel->getComments($id);
    $comment = $commentModel->getComment($id);
    ob_start();
    require __DIR__ . '/../views/news/indieNews.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function listNews(){
    $news = $this->model->getNews();
    ob_start();
    require __DIR__ . '/../views/news/listNews.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function index() {
    $news = $this->model->getNews();
    ob_start();
    require_once __DIR__ . '/../views/news/index.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function create() {
    $this->requireAdmin();
    $news = $this->model->getNews();
    ob_start();
    require_once __DIR__ . '/../views/news/create.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function store($postData){
    $this->requireAdmin();
    $this->model->create($postData);
    header('Location: /news');
  }
  public function edit($id){
    $new = $this->model->getNew($id);
    $medias = $this->media->getMedias();
    if($new == null){
      require __DIR__ . '/../views/errors/404.php';
      exit;
    }
    ob_start();
    require __DIR__ . '/../views/news/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    header('Location: /news');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /news');
    exit;
  }
}
