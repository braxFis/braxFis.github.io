<?php

namespace app\models;

require_once __DIR__ . '/../../bootstrap.php';

class Layout{
  private $db;
  private $file = __DIR__ . '/../../storage/layout.json';

  public function getLayout(){
    if(!file_exists($this->file)) return [
      "sidebar" => ["news", "search"],
      "footer" => ["contact"],
      "main" => []
    ];
    return json_decode(file_get_contents($this->file), true);
  }

  public function saveLayout($layout){
    file_put_contents($this->file, json_encode($layout, JSON_PRETTY_PRINT));
  }

}
