<?php

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;

require_once __DIR__ . '/../models/Page.php';

class PageController extends BaseController{
    private $model;

    public function __construct() {
        $this->model = new \app\models\Page;
    }

public function saveLayout() {
    $this->requireAdmin();
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);

    file_put_contents('debug.log', $raw);
    file_put_contents('debug_decoded.log', var_export($decoded, true));

    if (!isset($decoded['slug'], $decoded['layout'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Data saknas']);
        return;
    }

    $page = $this->model->getBySlug($decoded['slug']);
    if (!$page) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Sidan hittades inte']);
        return;
    }

    $saved = $this->model->saveLayout($decoded['layout']);
    echo json_encode([
        'success' => $saved,
        'message' => $saved ? 'Layout sparad!' : 'Kunde inte spara layout.'
    ]);
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

       public function create() {
        $this->requireAdmin();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/page/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function store() {
        $this->requireAdmin();
        // Vanligt form POST
        $data = [
            'title'   => $_POST['title'] ?? '',
            'slug'    => !empty($_POST['slug']) ? ltrim($_POST['slug'], '/') : strtolower(str_replace(' ', '-', $_POST['title'])),
            'content' => $_POST['content'] ?? '',
            'layout'  => $_POST['layout'] ?? ''
        ];

        $this->model->create($data);
        header('Location: /page');
        exit;
    }

    public function view($slug) {
        $page = $this->model->getBySlug($slug);
        if (!$page) {
            http_response_code(404);
            echo "Sidan hittades inte";
            return;
        }

        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        $layout = !empty($page->layout) ? json_decode($page->layout, true) : [];

        ob_start();
        require __DIR__ . '/../views/page/view.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    public function update($id, $data, $layout){
        $this->requireAdmin();
        $this->model->update($id, $data, $layout);
        if ($data === null) {
            $data = $_POST;
        }
        if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['image'])){
            die('Fill in all fields');
        }
        header('Location: /page');
        exit;
    }

    public function edit($id){
        $this->requireAdmin();
        $page = $this->model->getPage($id);
        if(!$page){
            die('Page not found');
        }
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . '/../views/page/edit.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }
    
    public function delete($id){
        $this->requireAdmin();
        $this->model->delete($id);
        header('Location: /page');
        exit;
    }
}
