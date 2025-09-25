<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . "/../models/Footer.php";

class FooterController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Footer;
    }

    public function index(){
        $footers = $this->model->getFooterItems();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . "/../views/footer/index.php";
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }
    public function create($data){
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'label' => isset($_POST['label']) ? $_POST['label'] : null
            ];
            $footerModel = new app\models\Footer;
            $footerItems = $footerModel->getFooterItems();
            $this->model->create($data);
        } else {
            $menus = (new Menu)->getMenuItems();
            $footers = (new Footer)->getFooterItems();
            ob_start();
            require __DIR__ . "/../views/footer/create.php";
            $content = ob_get_clean();
            include __DIR__ . '/../views/layout.php';
        }
    }

    public function store($postData){
        $this->requireAdmin();
        $this->model->create($postData);
        header('Location: /footer');
    }

    public function edit($id){
        $this->requireAdmin();
        $footer = $this->model->getFooterItem($id);
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . "/../views/footer/edit.php";
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function update($data, $id){
        $this->requireAdmin();
        if (empty($_POST['label']) || empty($_POST['url']) || empty($_POST['sort_order'])) {
            die("Title and description are required.");
        }
        $this->model->updateFooter($id, $data);
        header('Location: /footer');
        exit;
    }

    public function delete($id){
        $this->requireAdmin();
        $this->model->deleteFooter($id);
        header('Location: /footer');
        exit;
    }
}