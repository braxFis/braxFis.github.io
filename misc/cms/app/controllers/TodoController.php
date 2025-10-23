<?php

namespace app\controllers;

use app\models\Menu;
use app\models\Footer;
use app\interfaces\FeatureController;
// ----------------------------
// Todo
// ----------------------------
class TodoController extends BaseController implements FeatureController {

    private $model;

    public function __construct(){
        $this->model = new \app\models\Todo;
    }

    public function index(){
        $todos = $this->model->getTodos();
        ob_start();
        require_once __DIR__ . '/../views/todo/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function store($postData){
        $todos = new \app\models\Todo;

        $saved = $this->model->create(
            $postData
        );

        header('Location: /feature/todo');
    }

    public function create(){
        $this->requireAdmin();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require_once __DIR__ . '/../views/todo/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function edit($id){
    $todo = $this->model->getTodo($id);
    ob_start();
    require __DIR__ . '/../views/todo/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    header('Location: /feature/todo');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /feature/todo');
    exit;
  }

  public function getTodos(){
    $todos = $this->model->getTodos();
    $footers = (new Footer)->getFooterItems();
    $menus = (new Menu)->getMenuItems();
    ob_start();
    include __DIR__ . '/../views/todo/view.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }
}
