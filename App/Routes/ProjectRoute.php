<?php

namespace App\Routes;

class ProjectRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\ProjectCtrl';
    
    $app->get(
      '/test_projects',
      $pathCtrl.':test'
    );

    $app->get(
      '/projects', 
      $pathCtrl.':all'
    );

    $app->get(
      '/project/{id}',
      $pathCtrl.':byId'
    );

    $app->get(
      '/project_tags',
      $pathCtrl.':allProjectTags'
    );

    $app->get(
      '/project_tags/{id}',
      $pathCtrl.':projectTagsById'
    );

    $app->post(
      '/project_tag/create',
      $pathCtrl.':projectTagCreate'
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