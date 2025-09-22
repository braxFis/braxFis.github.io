<?php

namespace app\controllers;

require_once __DIR__ . "/../models/Dashboard.php";
require_once __DIR__ . "/../controllers/StatisticController.php";

class DashboardController extends BaseController {
  private $modelReview;
  private $modelPreview;
  private $modelStatistic;

  public function __construct() {
    $this->modelReview = new \app\models\Review;
    $this->modelPreview = new \app\models\Preview;
    $this->modelStatistic = new \app\models\Statistic;
  }

  public function index() {
    //Decide how the layout of the Dashboard should be
    $reviews = $this->modelReview->getReviews();
    $previews = $this->modelPreview->getPreviews();
    $statistics = $this->modelStatistic->showReviews();
    ob_start();
    require __DIR__ . "/../views/dashboard/index.php";
    $content = ob_get_clean();
    include __DIR__ . '/../views/layout.php';
  }



}

?>
