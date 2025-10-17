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

    public function index() {
        $pages = $this->model->getPages();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        ob_start();
        require __DIR__ . "/../views/page/index.php";
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

       public function create($slug) {
        $this->requireAdmin();
        $menus = (new Menu)->getMenuItems();
        $footers = (new Footer)->getFooterItems();
        $slug = $_POST['slug'] ?? null;
        ob_start();
        require __DIR__ . '/../views/page/create.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }


    public function store()
    {
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

        // Skapa ny Page-instans
        $page = new \app\models\Page();
        $page->title  = $title;
        $page->slug   = $slug !== '' ? $slug : $this->model->getBySlug($title);
        $page->layout = $layout;

        // Försök spara
        $saved = $this->model->create([
            'title' => $title,
            'slug'  => $slug,
            'layout'=> json_encode($layout, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ]);

        if ($saved) {
            header('Location: /page');
            exit;
        } else {
            echo "Kunde inte skapa sidan.";
        }
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
