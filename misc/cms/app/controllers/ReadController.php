<?php

namespace app\controllers;

use app\models\Read;

class ReadController extends BaseController
{
    private Read $model;

    public function __construct()
    {
        $this->model = new Read();
    }

    // Visa läslistan
    public function getReaderNew()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            header('Location: /login'); // eller exit med meddelande
            exit;
        }

        $readlist = $this->model->getReadList((int)$user_id);
        $footers = new \app\models\Footer;
        $menus = new \app\models\Menu;
        ob_start();
        include __DIR__ . '/../views/later/read_list_view.php';
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    // Hantera POST från formulär (ingen JS)
    public function addToReadList()
    {
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        $article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : null;

        if (!$user_id || !$article_id) {
            // fallback: redirect tillbaka med fel, eller visa meddelande
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }

        $this->model->add((int)$user_id, $article_id);

        // Redirect tillbaka där användaren kom ifrån (eller till läslistan)
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/read_list'));
        exit;
    }
}
