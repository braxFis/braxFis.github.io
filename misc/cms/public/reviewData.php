<?php

require_once 'C:\Users\Nick\Documents\GitHub\braxFis.github.io\misc\cms\app\models\Statistic.php';

$modelReview = new \app\models\Statistic;
$reviews = $modelReview->getReviewChartData();

// Skicka som JSON
header('Content-Type: application/json');
echo json_encode($reviews);
