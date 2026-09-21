<?php

namespace modules\movie\controllers;

use modules\movie\models\Movie;

class MovieController
{
  private $model;

  public function __construct()
  {
    $this->model = new Movie();
  }


  /*
   * Individual Movie Page
   */
  public function show($id){
    $movie = $this->model->getMovie($id);
    if(empty($movie)){
      http_response_code(404);
      echo "Movie not found";
      exit;
    }
    ob_start();
    require __DIR__ . '/../views/single_movie.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }
  /*
   * Movies page
   */
  public function index($page = 1): array
  {
    $data = $this->model->getMovies($page);

    $movies = [];

    foreach ($data['results'] as $item) {

      $item['genres'] = $this->model->getGenres($item);

      $item['certification'] = $this->model->getCertification($item['id']);

      $id = $item['id'];

      $item['description'] =
        $this->model->getDescription($id);

      $item['trailers'] =
        $this->model->getTrailers($id);

      $item['short_screenshots'] =
        $this->model->getScreenshots($id);

      $item['genres'] = $this->model->getGenres($item);

      $details = $this->model->getDetails($item);

      $item['budget'] = $details['budget'] ?? 0;
      $item['revenue'] = $details['revenue'] ?? 0;
      $item['runtime'] = $details['runtime'] ?? 0;

      $movies[] = $item;
    }

    ob_start();

    require __DIR__ . "/../views/index.php";

    $content = ob_get_clean();

    require __DIR__ . "/../../../app/views/layout.php";


    return $movies;
  }


  /*
   * Load more games
   *
   */
  public function loadMore($page = 1)
  {
    var_dump($page);
    exit;
    $data = $this->model->getGames($page);

    $games = $data['results'] ?? [];


    header('Content-Type: application/json');

    echo json_encode($games);

    exit;
  }
}
