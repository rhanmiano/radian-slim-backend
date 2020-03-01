<?php

namespace App\Routes;

class ProjectRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\ProjectCtrl';
    
    $app->get(
      '/projects', 
      $pathCtrl.':all'
    );

    $app->get(
      '/project/{id}',
      $pathCtrl.':byId'
    );

    $app->post(
      '/project/create',
      $pathCtrl.':create'
    );

    $app->put(
      '/project/update/{id}',
      $pathCtrl.':update'
    );

    $app->put(
      '/project/archive/{id}',
      $pathCtrl.':archive'
    );

    $app->put(
      '/project/restore/{id}',
      $pathCtrl.':restore'
    );

    $app->delete(
      '/project/delete/{id}',
      $pathCtrl.':delete'
    );

    $app->post(
      '/project/add_tag',
      $pathCtrl.':addProjectTag'
    );

  }

}