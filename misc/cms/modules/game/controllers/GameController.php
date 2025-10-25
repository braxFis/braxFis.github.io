<?php

namespace modules\game\controllers;

use modules\game\models\Game;
use modules\game\models\Gallery;

class GameController {
    private $model;

    public function __construct() {
        $this->model = new Game();
    }

    public function index($page = 1): array {
        $data = $this->model->getGames($page);
        $games = [];
        $model = new Gallery();
        $images = $model->getGallery();

        foreach ($data['results'] as $item) {
            $id = $item['id'];
            $item['description'] = $this->model->getDescription($id);
            $item['trailers'] = $this->model->getTrailers($id);
            $item['short_screenshots'] = $this->model->getScreenshots($id);
            $games[] = $item;
        }

        ob_start();
        require __DIR__ . "/../views/gallery.php";
        require __DIR__ . "/../views/index.php";
        $content = ob_get_clean();
        require __DIR__ . "/../../../app/views/layout.php";

        return $games;
    }

    public function review($page = 1): array {
        $data = $this->model->getGames($page);
        $games = [];
        $model = new Gallery();
        $images = $model->getGallery();

        foreach ($data['results'] as $item) {
            $id = $item['id'];
            $item['description'] = $this->model->getDescription($id);
            $item['trailers'] = $this->model->getTrailers($id);
            $games[] = $item;
        }

        ob_start();
        //require __DIR__ . "/../views/gallery.php";
        require __DIR__ . "misc/cms/app/views/reviews/indieReview.php";
        $content = ob_get_clean();
        require __DIR__ . "/../../../app/views/layout.php";

        return $games;
    }

}
