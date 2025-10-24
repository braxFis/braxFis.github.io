<?php
require_once __DIR__ . '/../models/Journey.php';

class JourneyController
{
    private $model;

    public function __construct()
    {
        $this->model = new JourneyModel();
    }

    public function index()
    {
        $journeys = [];
        include __DIR__ . '/../views/journey/index.php';
    }

    public function search()
    {
        $origin = $_GET['origin'] ?? '';
        $destination = $_GET['destination'] ?? '';

        $journeys = $this->model->searchJourney($origin, $destination);

        header('Content-Type: application/json');
        echo json_encode($journeys);
    }

    public function products()
    {
        $origin = $_GET['origin'] ?? '';
        $destination = $_GET['destination'] ?? '';

        $products = $this->model->searchProducts($origin, $destination);

        header('Content-Type: application/json');
        echo json_encode($products);
    }
}
