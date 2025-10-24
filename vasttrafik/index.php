<?php
// index.php
require_once __DIR__ . '/controllers/JourneyController.php';

$controller = new JourneyController();

$route = $_GET['route'] ?? 'journey/index';

list($controllerName, $action) = explode('/', $route);

switch ($controllerName) {
    case 'journey':
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            echo "Action not found";
        }
        break;

    default:
        echo "Controller not found";
        break;
}
