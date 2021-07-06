<?php

namespace App\Routes;

class CustomerRoute {

  public function __construct($app) {

    // $app->get('/test', 'App\Controllers\CustomerCtrl:test');
    $app->get('/customers', 'App\Controllers\CustomerCtrl:all');
    $app->get('/customer/{id}', 'App\Controllers\CustomerCtrl:byId');
    $app->post('/customer/create', 'App\Controllers\CustomerCtrl:create');
    $app->put('/customer/update/{id}', 'App\Controllers\CustomerCtrl:update');
    $app->delete('/customer/delete/{id}', 'App\Controllers\CustomerCtrl:delete');

  }

}