<?php 

namespace app\controllers;

use app\models\Footer;
use app\models\Menu;
use app\models\News;

require_once __DIR__ . '/../models/Read.php';

class ReadController extends BaseController{
    private $model;

    public function __construct(){
        $this->model = new \app\models\Read;
    }

    public function getReader(){
        //$news = new \app\models\News;
        //$news = $news->getNews();
        /**
         * Lägg till resterande funktionalitet nedan
         */
        // Om användaren klickat "Läs senare"
        if (isset($_GET['add'])) {
            $user_id = $_SESSION['user_id'] ?? null;
            $article_id = (int) $_GET['add'];

            $this->model->read($user_id, $article_id);
            exit; // Viktigt om du header-redirectar i modellen
        }
        include __DIR__ . '/../views/later/read.php';
    }

    public function removeRead(){
        $user_id = $_SESSION['user_id'] ?? null;
        $article_id = $_GET['id'] ?? null;

        $this->model->remove($user_id, $article_id);
        header('Location: /read_list');
    }

public function getReaderNew()
{
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        exit("Logga in för att se din läslista.");
    }

    $readlist = $this->model->getReadList($user_id);

    include __DIR__ . '/../views/later/read_list_view.php';
}

}