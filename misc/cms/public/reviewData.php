<?php

require_once __DIR__ . '/../app/models/Statistic.php';

$modelReview = new \app\models\Statistic;
$reviews = $modelReview->getReviewChartData();

// Skicka som JSON
header('Content-Type: application/json');
echo json_encode($reviews);
