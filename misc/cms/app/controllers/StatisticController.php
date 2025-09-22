<?php

namespace app\controllers;

require_once __DIR__ . '/../models/Statistic.php';
require_once __DIR__ . '/../models/Review.php';

class StatisticController extends BaseController{
  private $modelStatistic;
  private $modelReview;

  public function __construct(){
    $this->modelStatistic = new \app\models\Statistic;
    $this->modelReview = new \app\models\Review;
  }

  //public function reviewChart(){
  //  $reviews2 = $this->modelReview->getReviews();
  //  $data = [
  //    ['Titel', 'Subtitel']
  //  ];

  //  foreach($reviews2 as $review){
  //  $data[] = [$review->title, $review->subtitle];
  // }

   // require __DIR__ . '/../views/statistics/index.php';
   // }
  public function previewChart(){}


}
