<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;
use app\models\Media;

require_once __DIR__ . "/../models/Menu.php";
require_once __DIR__ . "/../models/Media.php";

class MenuController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Menu;
    }

    public function index(){
        $menus = $this->model->getMenuItems();
        $menus = (new Menu)->getMenuItems();
        $items = (new Media)->getMedias();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . "/../views/menu/index.php";
        $content = ob_get_clean();
        require __DIR__ . "/../views/layout.php";
    }

    public function create($data){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                'label' => isset($data['label']) ? $data['label'] : "",
            ];
            $menuModel = new app\models\Menu;
            $menuItems = $menuModel->getMenuItems();
            $this->model->create($data);
        } else{
            $menus = (new Menu)->getMenuItems();
            $footers = (new Footer)->getFooterItems();
            ob_start();
            require __DIR__ . "/../views/menu/create.php";
            $content = ob_get_clean();
            require __DIR__ . "/../views/layout.php";
        }
    }

    public function store($postData){
        $this->requireAdmin();
        $this->model->create($postData);
        header("Location: /menu");
    }

    public function edit($id){
        $this->requireAdmin();
        $menu = $this->model->getMenuItem($id);
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . "/../views/menu/edit.php";
        $content = ob_get_clean();
        require __DIR__ . "/../views/layout.php";
    }

    public function update($data, $id){
        $this->requireAdmin();
        if (empty($_POST['label']) || empty($_POST['url']) || empty($_POST['sort_order'])) {
            die("Title and description are required.");
        }
        $this->model->updateMenu($id, $data);
        header("Location: /menu");
        exit;
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->deleteMenu($id);
        header("Location: /menu");
        exit;
    }
}