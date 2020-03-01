<?php

$rootPath = dirname(dirname(__FILE__));
require $rootPath  . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create($rootPath);
$dotenv->load();

$app = (new App\Init())->getApp();

$app->run();