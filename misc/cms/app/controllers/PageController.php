<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . '/../models/Page.php';

class PageController extends BaseController{
  private $model;

  public function __construct()
  {
    $this->model = new \app\models\Page;
  }

  public function index() {
    $pages = $this->model->getPages();
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require __DIR__ . "/../views/page/index.php";
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function create(){
    $this->requireAdmin();
    $pageModel = new \app\models\Page;
    $pages = $pageModel->getPages();
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require __DIR__ . "/../views/page/create.php";
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function store($postData){
    $this->requireAdmin();
    // Pass to model
    $this->model->create($postData);
    header('Location: /page');
  }

  public function view($slug){
    $page = $this->model->getBySlug($slug);
    require __DIR__ . '/../views/page/view.php';
  }

  public function edit($id){
    $this->requireAdmin();
    $page = $this->model->getPage($id);
    $menus = (new Menu)->getMenuItems();
    $footers = (new Footer)->getFooterItems();
    ob_start();
    require __DIR__ . "/../views/page/edit.php";
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    if ($data === null) {
      $data = $_POST;
    }
    if(empty($_POST['title']) || empty($_POST['slug']) || empty($_POST['content'])){
      die('Fill in all fields');
    }
    header('Location: /page');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /page');
    exit;
  }

}
