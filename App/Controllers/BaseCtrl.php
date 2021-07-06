<?php

namespace App\Controllers;

class BaseCtrl{

  protected $validator;
  protected $crypto;

  protected $res = array (

    'success' => false,
    'message' => '',

  );

  public function __construct($app){

    $this->validator = $app->get('validator');
    $this->crypto = new \App\Helpers\CryptoHelper;
    
  }

  public function getValidator(){

    return $this->validator;

  }
  
}