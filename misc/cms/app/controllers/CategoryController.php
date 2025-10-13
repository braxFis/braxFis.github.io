<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;

require_once  __DIR__ . "/../models/Category.php";

class CategoryController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Category;
    }

    public function index(){
        $categories = $this->model->getCategories();
        require __DIR__ . '/../views/admin/categories/index.php';
    }

    public function create(){
        $categoryModel = new \app\models\Category;
        $categories = $categoryModel->getCategories();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/admin/categories/create.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

    public function store($postData){
        $this->model->create($postData);
        header('Location: /admin/categories');
        exit;
    }

    public function edit($id){
        $category = $this->model->getCategory($id);
        if($category == null){
            require __DIR__ . '/app/views/errors/404.php';
            exit;
        }
        require __DIR__ .  '/../views/admin/categories/edit.php';
    }

    public function update($id, $data){
        $this->model->updateCategory($id, $data);
        header('Location: /admin/categories');
        exit;
    }

    public function delete($id){
        $this->model->delete($id);
        header('Location: /admin/categories');
        exit;
    }

    public function posts($id){
        $category = $this->model->getCategory($id);
        $posts = $this->model->getPosts($id);
        if (!$category) {
            require __DIR__ . '/../views/errors/404.php';
            exit;
        }

        $menus = (new Menu)->getMenuItems();
        $footer = (new Footer)->getFooterItems();
        ob_start();
        // ✅ Låt dessa variabler gå direkt till vyn
        require __DIR__ . '/../views/admin/categories/posts.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }

}