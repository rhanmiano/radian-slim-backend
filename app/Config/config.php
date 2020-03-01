<?php

namespace App\Config;

use PDO;

class Config {

  public function slimSettings(){
    return array(
      'settings' => array(
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'routerCacheFile' => '',
      )
    );
  }

  public function db(){
    return array (
      'connection_string' => 'mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME').';charset=utf8mb4',
      'username' => getenv('DB_USER'),
      'password' => getenv('DB_PASS'),
      'return_result_sets' => false,
      'error_mode' => PDO::ERRMODE_WARNING,
    );
  }

}