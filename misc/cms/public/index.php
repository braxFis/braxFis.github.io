<?php

require_once __DIR__ . '/../bootstrap.php'; // ✅ Garanterat rätt path

//echo "Requested URI: " . $_SERVER['REQUEST_URI'];
// index.php (din befintliga)
//require __DIR__ . '/../core/PluginManager.php';

//Extract the path component from the full URL of the current request
$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request = trim($request, '/');

$routes = [

    //Förbered route för senare användning
    //När du så småningom sätter igång med skriveriet
    'EDITOR' => [
        'GET' => [
            '' => ''
        ],
        'POST'=> [
            ''=> ''
        ]
    ],

    'ADMIN' => [
    'GET' => [
    
     //Read Later
     'read_list' => ['controller' => '\app\controllers\ReadController', 'method' => 'getReader'],
     'remove_read' => ['controller' => '\app\controllers\ReadController', 'method' => 'removeRead'],
     'read_list_improved' => ['controller' => '\app\controllers\ReadController', 'method' =>'getReaderNew'],

     //Features -> Share
     'feature/share' => ['controller' => '\app\controllers\ShareController', 'method' => 'index'],
     'feature/share/create' => ['controller' => '\app\controllers\ShareController', 'method' => 'create'],
     'feature/share/edit/([0-9]+)' => ['controller' => '\app\controllers\ShareController', 'method' => 'edit'],

     //Features -> Todo
     'feature/todo' => ['controller' => '\app\controllers\TodoController', 'method' => 'index'],
     'feature/todo/create' => ['controller' => '\app\controllers\TodoController', 'method' => 'create'],
     'feature/todo/edit/([0-9]+)' => ['controller' => '\app\controllers\TodoController', 'method' => 'edit'],

     //Features -> Wishlist
     'feature/wishlist' => ['controller' => '\app\controllers\WishlistController', 'method' => 'index'],
     'feature/wishlist/create' => ['controller' => '\app\controllers\WishlistController', 'method' => 'create'],
     'feature/wishlist/edit/([0-9]+)' => ['controller' => '\app\controllers\WishlistController', 'method' => 'edit'],

     //Posts
     'posts' => ['controller' => '\app\controllers\AdminController', 'method' => 'index'],
     'posts/show/([0-9]+)' => ['controller' => '\app\controllers\PostController', 'method' => 'show'],
     'posts/create' => ['controller' => '\app\controllers\AdminController', 'method' => 'create'],
     'posts/edit/([0-9]+)' => ['controller' => '\app\controllers\AdminController', 'method' => 'edit'],

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

      //Drag and Drop
      'page/save-layout' => ['controller' => '\app\controllers\DragDropController', 'method' => 'save'],

      //Media
      'media/create'  => ['controller' => '\app\controllers\MediaController', 'method' => 'create'],
      'media/edit/([0-9]+)' => ['controller' => '\app\controllers\MediaController', 'method' => 'edit'],
      'media'   => ['controller' => '\app\controllers\MediaController', 'method' => 'index'],

      //Plugins
      'plugins' =>  ['controller' => '\app\controllers\PluginController', 'method' => 'index'],
      'plugins/view/([0-9]+)' =>  ['controller' => '\app\controllers\PluginController', 'method' => 'view'],
      'plugins/install' =>  ['controller' => '\app\controllers\PluginController', 'method' => 'install'],
      'plugins/edit/([0-9]+)' =>  ['controller' => '\app\controllers\PluginController', 'method' => 'edit'],

      //Drag and Drop
      'dragdrop/load' => ['controller' => '\app\controllers\DragDropController', 'method' => 'load'],

      'dragdrop/editor' => ['controller' => '\app\controllers\DragDropController', 'method' => 'editor'],

      //Plan
      'plan' => ['controller' => '\app\controllers\PlanController', 'method' => 'index'],
      'plan/edit/([0-9]+)' => ['controller' => '\app\controllers\PlanController', 'method' => 'edit'],
      'plan/create' => ['controller' => '\app\controllers\PlanController', 'method' => 'create'],

    ],
    'POST' => [

      //Share
      'feature/share/store' => ['controller' => '\app\controllers\ShareController', 'method' => 'store'],
      'feature/share/update/([0-9]+)' => ['controller' => '\app\controllers\ShareController', 'method' => 'update'],
      'feature/share/delete/([0-9]+)' => ['controller' => '\app\controllers\ShareController', 'method' => 'delete'],

      //Todo
      'feature/todo/store' => ['controller' => '\app\controllers\TodoController', 'method' => 'store'],
      'feature/todo/update/([0-9]+)' => ['controller' => '\app\controllers\TodoController', 'method' => 'update'],
      'feature/todo/delete/([0-9]+)' => ['controller' => '\app\controllers\TodoController', 'method' => 'delete'],

      //Wishlist
      'feature/wishlist/store' => ['controller' => '\app\controllers\WishlistController', 'method' => 'store'],
      'feature/wishlist/update/([0-9]+)' => ['controller' => '\app\controllers\WishlistController', 'method' => 'update'],
      'feature/wishlist/delete/([0-9]+)' => ['controller' => '\app\controllers\WishlistController', 'method' => 'delete'],

      //Plan
      'plan/store'  => ['controller' => '\app\controllers\PlanController', 'method' => 'store'],
      'plan/update/([0-9]+)' => ['controller' => '\app\controllers\PlanController', 'method' => 'update'],
      'plan/delete/([0-9]+)' => ['controller' => '\app\controllers\PlanController', 'method' => 'delete'],

      //Drag and Drop
      'dragdrop/save' => ['controller' => '\app\controllers\DragDropController', 'method' => 'save'],

      //Posts
     'posts/store'  => ['controller' => '\app\controllers\AdminController', 'method' => 'store'],
     'posts/update/([0-9]+)' => ['controller' => '\app\controllers\AdminController', 'method' => 'update'],
     'posts/delete/([0-9]+)' => ['controller' => '\app\controllers\AdminController', 'method' => 'delete'],

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
      'footer/update' => ['controller' => '\app\controllers\FooterController', 'method' => 'updateOrder'],

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
      'plugins/delete/([0-9]+)' => ['controller' => '\app\controllers\PluginController', 'method' => 'delete'],
      'plugins/update/([0-9]+)' => ['controller' => '\app\controllers\PluginController', 'method' => 'update'],

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

          //Regular
          'regular' => ['controller' => '\app\controllers\RegularController', 'method' => 'index'],

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
          'search' => ['controller' => '\modules\game\controllers\SearchController', 'method' => 'index'],
          'trailers' => ['controller' => '\modules\game\controllers\TrailerController', 'method' => 'index'],
          '' => ['controller' => '\modules\game\controllers\GameController', 'method' => 'index'],
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
