<?php

namespace App;

require_once('Constants.php');

class Init {

  private $app;

  public function __construct() {
    $this->app = new \Slim\App(\App\Config\Config::slimSettings());
    $this->dependencies();
    $this->middleware();
    $this->routes();
  }


  public function getApp() {
    return $this->app;
  }


  private function dependencies() {
    return new \App\Dependencies($this->getApp());
  }


  private function middleware() {
    return new \App\Middleware($this->getApp());
  }


  private function routes() {    
    $routes = null;

    if (is_dir(APP_PATH . '/Routes')) {
      $routes = scandir(APP_PATH . '/Routes');
    }

    $dump_routes = array();
    if ($routes !== null && gettype($routes) === 'array'){
      foreach ($routes as $route) {
        if (strpos($route, '.php')) {
          $route_namespace = 'App\\Routes\\' . rtrim($route, (substr($route, -4)));
          array_push($dump_routes, new $route_namespace($this->getApp()));
        }
      }
    }

    return $dump_routes;
  }
  
}