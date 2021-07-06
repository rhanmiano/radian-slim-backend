<?php

namespace App\Routes;

class TagRoute {

  public function __construct($app) {
    
    $pathCtrl = 'App\Controllers\Main\TagCtrl';
    
    $app->get(
        '/tags', 
        $pathCtrl.':all'
    );

    $app->get(
        '/tag/{id}',
        $pathCtrl.':byId'
    );

    $app->post(
        '/tag/create',
        $pathCtrl.':create'
    );

    $app->put(
        '/tag/update/{id}',
        $pathCtrl.':update'
    );

    $app->put(
        '/tag/archive/{id}',
        $pathCtrl.':archive'
    );

    $app->put(
        '/tag/restore/{id}',
        $pathCtrl.':restore'
    );

    $app->delete(
        '/tag/delete/{id}',
        $pathCtrl.':delete'
    );

  }

}