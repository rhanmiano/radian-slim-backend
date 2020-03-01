<?php

namespace App\Routes;

class AuthenticationRoute {

  public function __construct($app) {

    $app->get('/test-login', 'App\Controllers\Authentication\LoginCtrl:test');
    $app->post('/login', 'App\Controllers\Authentication\LoginCtrl:login');

  }

}