<?php

namespace App;


class Dependencies {

  private $container;


  public function __construct($app) {
    $this->container = $app->getContainer();
    $this->inject();
  }


  private function inject() {
    $this->container['validator'] = function($c) {
        return new \App\Validation\Validator;
    };
  }
}