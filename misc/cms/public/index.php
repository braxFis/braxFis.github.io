<?php

require_once __DIR__ . '/../bootstrap.php'; // ✅ Garanterat rätt path

//echo "Requested URI: " . $_SERVER['REQUEST_URI'];

//Extract the path component from the full URL of the current request
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = trim($request, '/');

$routes = [

    'ADMIN' => [
    'GET' => [

      //Reviews
      'review' => ['controller' => '\app\controllers\ReviewController', 'method' => 'index'],
      'review/create' => ['controller' => '\app\controllers\ReviewController', 'method' => 'create'],
      'review/edit/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'edit'],

      //Previews
      'preview' => ['controller' => '\app\controllers\PreviewController', 'method' => 'index'],
      'preview/create'  => ['controller' => '\app\controllers\PreviewController', 'method' => 'create'],
      'preview/edit/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'edit']

    ],
    'POST' => [

      //Reviews
      'review/store' => ['controller' => '\app\controllers\ReviewController', 'method' => 'store'],
      'review/delete/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'delete'],
      'review/update/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'update'],

      //Previews
      'preview/store'  => ['controller' => '\app\controllers\PreviewController', 'method' => 'store'],
      'preview/delete/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'delete'],
      'preview/update/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'update'],
    ]
    ],
    'USER' => [
        'GET' => [
          'profile' => ['controller' => '\app\controllers\ProfileController', 'method' => 'viewProfile'],
          'profile/edit' => ['controller' => '\app\controllers\ProfileController', 'method' => 'editProfile'],
          'profile/confirm-delete' => ['controller' => '\app\controllers\ProfileController', 'method' => 'confirmDelete'],
        ],
        'POST' => [
          'profile/update' => ['controller' => '\app\controllers\ProfileController', 'method' => 'updateProfile'],
          'profile/delete' => ['controller' => '\app\controllers\ProfileController', 'method' => 'deleteProfile'],
        ]
    ],

    'PUBLIC' => [
        'GET' => [
          '' => ['controller' => '\app\controllers\DashboardController', 'method' => 'index'],
          'login' => ['controller' => '\app\controllers\UserController', 'method' => 'login'],
          'register' => ['controller' => '\app\controllers\UserController', 'method' => 'register'],
          'logout' => ['controller' => '\app\controllers\UserController', 'method' => 'logout'],
        ],
        'POST' => [
          'login' => ['controller' => '\app\controllers\UserController', 'method' => 'login'],
          'register' => ['controller' => '\app\controllers\UserController', 'method' => 'register'],
        ]
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
