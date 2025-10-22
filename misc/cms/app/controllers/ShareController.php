<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;
use app\interfaces\FeatureController;
// ----------------------------
// Share
// ----------------------------
class ShareController extends BaseController implements FeatureController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Share;
    }

    public function index(){
        $shares = $this->model->getShares();
        ob_start();
        require_once __DIR__ . '/../views/share/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function store($postData){
        $shares = new \app\models\Share;

        $saved = $this->model->create(
            $postData
        );

        header('Location: /feature/share');
    }

    public function create(){
        $this->requireAdmin();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require_once __DIR__ . '/../views/share/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function edit($id){
    $share = $this->model->getShare($id);
    ob_start();
    require __DIR__ . '/../views/share/edit.php';
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }

  public function update($id, $data){
    $this->requireAdmin();
    $this->model->update($id, $data);
    header('Location: /feature/share');
    exit;
  }

  public function delete($id){
    $this->requireAdmin();
    $this->model->delete($id);
    header('Location: /feature/share');
    exit;
  }

}
