<?php
namespace modules\game\controllers;

use modules\game\models\Trailer;

class TrailerController {
    private $model;

    public function __construct() {
        $this->model = new Trailer();
    }

    public function index() {
        $maxPages = 2; // exempel: 2 sidor (ändra till 10 om du vill)
        $gamesWithTrailers = [];

        for ($page = 1; $page <= $maxPages; $page++) {
            $games = $this->model->getGamesPage($page);

            foreach ($games as $game) {
                $trailers = $this->model->getGameTrailers($game['id']);
                if (!empty($trailers)) {
                    $gamesWithTrailers[] = [
                        'name' => $game['name'],
                        'genres' => array_column($game['genres'], 'name'),
                        'trailers' => $trailers
                    ];
                }
            }
        }

        // skicka till vyn
        require __DIR__ . "/../views/trailer.php";
    }
}
