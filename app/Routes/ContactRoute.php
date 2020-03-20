<?php

namespace App\Routes;

class ContactRoute {

  public function __construct($app) {
    
    $pathCtrl = 'App\Controllers\Main\ContactCtrl';
    
    $app->post(
        '/message/save', 
        $pathCtrl.':saveMessage'
    );

  }

}