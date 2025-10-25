<?php

namespace app\controllers;

// bootstrap
require __DIR__ . '/autoload.php';

use app\controllers\ReadController;
use app\services\ScreenshotService;

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
    
    
    // Pass to view
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

        // Sanera och hämta värden
        $title  = trim($_POST['title'] ?? '');
        $slug   = trim($_POST['slug'] ?? '');
        $layout = $_POST['layout'] ?? '';

        // Om layouten är tom eller ogiltig -> använd tom array
        if (empty($layout)) {
            $layout = [];
        } else {
            // Försök tolka JSON om den redan skickas som text
            $decoded = json_decode($layout, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $layout = [];
            } else {
                $layout = $decoded;
            }
        }

        // Validering
        if ($title === '') {
            die('Titel får inte vara tom');
        }

        // Skapa ny News-instans
        $news = new \app\models\News();
        $news->title  = $title;
        //$news->slug   = $slug !== '' ? $slug : $this->model->getBySlug($title);
        $news->layout = $layout;

        // Försök spara
        $saved = $this->model->create(
            //'title' => $title,
            //'slug'  => $slug,
            //'layout'=> json_encode($layout, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            $postData
        );

        if ($saved) {
            header('Location: /news');
            exit;
        } else {
            echo "Kunde inte skapa sidan.";
        }
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
