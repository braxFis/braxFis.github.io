<?php

namespace app\widgets;

use app\models\Todo;

class TodoWidget{
  public static function renderButton(){
    return "<button type='submit' class='save-btn'>Skapa Todo</button>";
  }
}
