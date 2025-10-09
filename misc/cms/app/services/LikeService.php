<?php

namespace app\services;

class LikeService{

  private $model;

  public function __construct($model)
  {
    $this->model = $model;
  }

  function displayButtons()
  {
  }
}
