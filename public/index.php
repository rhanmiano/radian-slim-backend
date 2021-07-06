<?php

$rootPath = dirname(dirname(__FILE__));
// var_dump($rootPath); die();
require $rootPath  . '/vendor/autoload.php';

if (file_exists($rootPath . '/.env')) {
  $dotenv = Dotenv\Dotenv::create($rootPath);
  $dotenv->load();
}

if (getenv('ENVIRONMENT') === false) {
  echo 'Application\'s environment configuration has not been set up properly.'; die();
}

$app = (new \App\Init())->getApp();

$app->run();