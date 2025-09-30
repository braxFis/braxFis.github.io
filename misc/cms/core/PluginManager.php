<?php
// core/PluginManager.php
class PluginManager {
  private $plugins = [];

  public function loadPlugins($pluginDir = __DIR__ . '/../plugins') {
    foreach (glob($pluginDir . '/*', GLOB_ONLYDIR) as $dir) {
      $mainFile = $dir . '/' . basename($dir) . '.php';
      if (file_exists($mainFile)) {
        require_once $mainFile;
        $className = basename($dir);
        if (class_exists($className)) {
          $this->plugins[$className] = new $className();
        }
      }
    }
  }

  public function runHook($hook, ...$args) {
    foreach ($this->plugins as $plugin) {
      if (method_exists($plugin, $hook)) {
        call_user_func_array([$plugin, $hook], $args);
      }
    }
  }

  public function registerRoutes(&$routes) {
    echo "Registrerar HelloWorld routes";
    $routes['ADMIN']['GET']['/hello'] = [
      'controller' => '\app\controllers\PluginController',
      'method' => 'index'
    ];
  }
}
