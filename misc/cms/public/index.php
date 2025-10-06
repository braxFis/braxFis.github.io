<?php

require_once __DIR__ . '/../bootstrap.php'; // ✅ Garanterat rätt path

//echo "Requested URI: " . $_SERVER['REQUEST_URI'];
// index.php (din befintliga)
require __DIR__ . '/../core/PluginManager.php';

//Extract the path component from the full URL of the current request
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = trim($request, '/');

$routes = [

    'ADMIN' => [
    'GET' => [
      //Reviews
      'review' => ['controller' => '\app\controllers\ReviewController', 'method' => 'listReview'],
      'review/list' => ['controller' => '\app\controllers\ReviewController', 'method' => 'index'],
      'review/create' => ['controller' => '\app\controllers\ReviewController', 'method' => 'create'],
      'review/edit/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'edit'],

      //Previews
      'preview' => ['controller' => '\app\controllers\PreviewController', 'method' => 'listPreview'],
      'preview/list' =>  ['controller' => '\app\controllers\PreviewController', 'method' => 'index'],
      'preview/create'  => ['controller' => '\app\controllers\PreviewController', 'method' => 'create'],
      'preview/edit/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'edit'],

      //News
      'news' => ['controller' => '\app\controllers\NewsController', 'method' => 'listNews'],
      'news/list' => ['controller' => '\app\controllers\NewsController', 'method' => 'index'],
      'news/create' => ['controller' => '\app\controllers\NewsController', 'method' => 'create'],
      'news/edit/([0-9]+)' => ['controller' => '\app\controllers\NewsController', 'method' => 'edit'],

      //Menu
      'menu' => ['controller' => '\app\controllers\MenuController', 'method' => 'index'],
      'menu/edit/([0-9]+)' => ['controller' => '\app\controllers\MenuController', 'method' => 'edit'],
      'menu/create' => ['controller' => '\app\controllers\MenuController', 'method' => 'create'],

      //Footer
      'footer/create' => ['controller' => '\app\controllers\FooterController', 'method' => 'create'],
      'footer/edit/([0-9]+)' => ['controller' => '\app\controllers\FooterController', 'method' => 'edit'],
      'footer'  => ['controller' => '\app\controllers\FooterController', 'method' => 'index'],

      //Page
      'page' =>  ['controller' => '\app\controllers\PageController', 'method' => 'index'],
      'page/create'  => ['controller' => '\app\controllers\PageController', 'method' => 'create'],
      'page/edit/([0-9]+)' =>  ['controller' => '\app\controllers\PageController', 'method' => 'edit'],

      'page/{slug}' => ['controller' => '\app\controllers\PageController', 'method' => 'view'],

      //Media
      'media/create'  => ['controller' => '\app\controllers\MediaController', 'method' => 'create'],
      'media/edit/([0-9]+)' => ['controller' => '\app\controllers\MediaController', 'method' => 'edit'],
      'media'   => ['controller' => '\app\controllers\MediaController', 'method' => 'index'],

      //Plugins
      'plugins' =>  ['controller' => '\app\controllers\PluginController', 'method' => 'index'],
      'plugins/install' =>  ['controller' => '\app\controllers\PluginController', 'method' => 'install'],

      //DnD
      'dnd' => ['controller' => '\app\controllers\LayoutController', 'method' => 'save']

    ],
    'POST' => [

      //DnD
      'item/index' => ['controller' => '\app\controllers\ItemController', 'method' => 'index'],

      //News
      'news/store' => ['controller' => '\app\controllers\NewsController', 'method' => 'store'],
      'news/delete/([0-9]+)' => ['controller' => '\app\controllers\NewsController', 'method' => 'delete'],
      'news/update/([0-9]+)' => ['controller' => '\app\controllers\NewsController', 'method' => 'update'],

      //Reviews
      'review/store' => ['controller' => '\app\controllers\ReviewController', 'method' => 'store'],
      'review/delete/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'delete'],
      'review/update/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'update'],

      //Previews
      'preview/store'  => ['controller' => '\app\controllers\PreviewController', 'method' => 'store'],
      'preview/delete/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'delete'],
      'preview/update/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'update'],

      //Menu
      'menu/store' => ['controller' => '\app\controllers\MenuController', 'method' => 'store'],
      'menu/update/([0-9]+)' => ['controller' => '\app\controllers\MenuController', 'method' => 'update'],
      'menu/delete/([0-9]+)' => ['controller' => '\app\controllers\MenuController', 'method' => 'delete'],

      //Footer
      'footer/delete/([0-9]+)' => ['controller' => '\app\controllers\FooterController', 'method' => 'delete'],
      'footer/update/([0-9]+)' => ['controller' => '\app\controllers\FooterController', 'method' => 'update'],
      'footer/store' => ['controller' => '\app\controllers\FooterController', 'method' => 'store'],

      //Page
      'page/store' => ['controller' => '\app\controllers\PageController', 'method' => 'store'],
      'page/delete/([0-9]+)' => ['controller' => '\app\controllers\PageController', 'method' => 'delete'],
      'page/update/([0-9]+)' => ['controller' => '\app\controllers\PageController', 'method' => 'update'],

      //Media
      'media/delete/([0-9]+)' => ['controller' => '\app\controllers\MediaController', 'method' => 'delete'],
      'media/update/([0-9]+)' => ['controller' => '\app\controllers\MediaController', 'method' => 'update'],
      'media/store' => ['controller' => '\app\controllers\MediaController', 'method' => 'store'],

      //Plugins
      'plugins/store' => ['controller' => '\app\controllers\PluginController', 'method' => 'store'],
    ]
    ],
    'USER' => [
        'GET' => [

          //Comments
          'comments' => ['controller' => '\app\controllers\CommentController', 'method' => 'store'],
          'comments/edit/([0-9]+)' => ['controller' => '\app\controllers\CommentController', 'method' => 'edit'],

          //Profile
          'profile' => ['controller' => '\app\controllers\ProfileController', 'method' => 'viewProfile'],
          'profile/edit' => ['controller' => '\app\controllers\ProfileController', 'method' => 'editProfile'],
          'profile/confirm-delete' => ['controller' => '\app\controllers\ProfileController', 'method' => 'confirmDelete'],

          //Subscribe
          'subscribe' => ['controller' => '\app\controllers\SubscribeController', 'method' => 'store'],
          'subscribe/edit' => ['controller' => '\app\controllers\SubscribeController', 'method' => 'edit'],
        ],
        'POST' => [

          //Profile
          'profile/update' => ['controller' => '\app\controllers\ProfileController', 'method' => 'updateProfile'],
          'profile/delete' => ['controller' => '\app\controllers\ProfileController', 'method' => 'deleteProfile'],

          //Comments
          'comments/store' => ['controller' => '\app\controllers\CommentController', 'method' => 'store'],
          'comments/delete/([0-9]+)' => ['controller' => '\app\controllers\CommentController', 'method' => 'delete'],
          'comments/update/([0-9]+)' => ['controller' => '\app\controllers\CommentController', 'method' => 'update'],
        ]
    ],

    'PUBLIC' => [
        'GET' => [

          'news/indie/([0-9]+)' => ['controller' => '\app\controllers\NewsController', 'method' => 'indieNews'],
          'review/indie/([0-9]+)' => ['controller' => '\app\controllers\ReviewController', 'method' => 'indieReview'],
          'preview/indie/([0-9]+)' => ['controller' => '\app\controllers\PreviewController', 'method' => 'indiePreview'],
          'dashboard' => ['controller' => '\app\controllers\DashboardController', 'method' => 'index'],
          'stat/view' => ['controller' => '\app\controllers\StatisticController', 'method' => 'reviewChart'],
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

//Load plugins
$plugins = new PluginManager();
$plugins->loadPlugins();

$plugins->registerRoutes($routes);

foreach ($routes['ADMIN'][$method] as $route => $info) {
  $pattern = preg_replace(['#\{id\}#', '#\{slug\}#'], ['([0-9]+)', '([a-zA-Z0-9\-]+)'], $route);
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
    $pattern = preg_replace('#/a-zA-Z([0-9]+)#', '/([a-zA-Z0-9]+)', $route);
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
