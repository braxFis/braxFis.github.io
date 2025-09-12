<?php

require_once __DIR__ . '/../bootstrap.php'; // ✅ Garanterat rätt path

//echo "Requested URI: " . $_SERVER['REQUEST_URI'];

//Extract the path component from the full URL of the current request
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = trim($request, '/');

$routes = [

    'ADMIN' => [
    'GET' => [],
    'POST' => []
    ],
    'USER' => [
        'GET' => [],
        'POST' => []
    ],

    'PUBLIC' => [
        'GET' => [],
        'POST' => []
    ]
];
$path = $request;

$method = $_SERVER['REQUEST_METHOD'];

foreach ($routes['ADMIN'][$method] as $route => $info) {
    $pattern = preg_replace('#/([0-9]+)#', '/([0-9]+)', $route);
    if(preg_match("#^$pattern$#", $path, $matches)){
        //var_dump(class_exists('\app\controllers\PostController'));
        //var_dump(get_included_files());
        $controller = new $info['controller'];
        $id = isset($matches[1]) ? $matches[1] : null;

        if($method == 'POST' && $info['method'] !== 'delete'){
            $controller->{$info['method']}($_POST, $id);
        } else{
            $controller->{$info['method']}($id);
        }
        exit;
    }
}

foreach ($routes['USER'][$method] as $route => $info) {
    $pattern = preg_replace('#/([0-9]+)#', '/([0-9]+)', $route);
    if(preg_match("#^$pattern$#", $path, $matches)){
        //var_dump(class_exists('\app\controllers\PostController'));
        //var_dump(get_included_files());
        $controller = new $info['controller'];
        $id = isset($matches[1]) ? $matches[1] : null;

        if($method == 'POST' && $info['method'] !== 'delete'){
            $controller->{$info['method']}($_POST, $id);
        } else{
            $controller->{$info['method']}($id);
        }
        exit;
    }
}

foreach ($routes['PUBLIC'][$method] as $route => $info) {
    $pattern = preg_replace('#/([0-9]+)#', '/([0-9]+)', $route);
    if(preg_match("#^$pattern$#", $path, $matches)){
        //var_dump(class_exists('\app\controllers\PostController'));
//        var_dump(get_included_files());
        $controller = new $info['controller'];
        $id = isset($matches[1]) ? $matches[1] : null;

        if($method == 'POST' && $info['method'] !== 'delete'){
            $controller->{$info['method']}($_POST, $id);
        } else{
            $controller->{$info['method']}($id);
        }
        exit;
    }
}

    http_response_code(404);
    require  __DIR__ . '/../app/views/404.php';
    exit;
