<?php

namespace App\Routes;

class CategoryRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\CategoryCtrl';
    
    $app->get(
        '/categories', 
        $pathCtrl.':all'
    );

    $app->get(
        '/category/{id}',
        $pathCtrl.':byId'
    );

    $app->post(
        '/category/create',
        $pathCtrl.':create'
    );

    $app->put(
        '/category/update/{id}',
        $pathCtrl.':update'
    );

    $app->put(
        '/category/archive/{id}',
        $pathCtrl.':archive'
    );

    $app->put(
        '/category/restore/{id}',
        $pathCtrl.':restore'
    );

    $app->delete(
        '/category/delete/{id}',
        $pathCtrl.':delete'
    );

  }

}