<?php

namespace app\controllers;

class BaseController{
  protected function requireAdmin(){
    if(!$_SESSION['role'] || $_SESSION['role'] != "admin"){
      http_response_code(403);
      exit("Access denied");
    }
  }

  protected function requireUser(){
    if(!$_SESSION['role'] || $_SESSION['role'] != "user"){
      http_response_code(403);
      exit("Access denied");
    }
  }
}
