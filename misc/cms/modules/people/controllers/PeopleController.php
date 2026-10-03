<?php

namespace modules\people\controllers;

use modules\people\models\People;

class PeopleController
{
  private $model;

  public function __construct()
  {
    $this->model = new People();
  }


  /*
   * People page
   */
  public function index(): array
  {
    $people = $this->model->getPeople();

    ob_start();

    require __DIR__ . '/../views/index.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';

    return $people;
  }


  /*
   * Individual Person Page
   */
  public function show($id)
  {
    $person = $this->model->getPerson($id);

    if (empty($person)) {
      http_response_code(404);
      echo "Person not found";
      exit;
    }

    $movies = $this->model->getMovieCredits($id);

    ob_start();

    require __DIR__ . '/../views/single_people.php';

    $content = ob_get_clean();

    require __DIR__ . '/../../../app/views/layout.php';
  }
}
