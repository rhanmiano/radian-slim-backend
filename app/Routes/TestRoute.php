<?php

namespace App\Routes;

class TestRoute {

  public function __construct($app) {
    
    $pathCtrl = 'App\Controllers\TestEmailCtrl';
    
    $app->post(
        '/test_email', 
        $pathCtrl.':testEmail'
    );

  }

}