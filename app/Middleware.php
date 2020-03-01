<?php

namespace App;

use Slim\Middleware\JwtAuthentication;
use Slim\Middleware\JwtAuthentication\RequestPathRule;
use Slim\Middleware\JwtAuthentication\RequestMethodRule;
use Slim\Exception\NotFoundException;

class Middleware {

  private $app;
  private $container;

  public function __construct($app) {
    $this->app = $app;
    $this->container = $app->getContainer();

    // Security Headers
    $this->app->add(function($req, $res, $next) {
      $response = $next($req, $res);
             
      return $response
              // enable CORS
              ->withHeader('Access-Control-Allow-Origin', '*')
              ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
              ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
              // avoid clickjacking
              ->withHeader('X-Frame-Options', 'deny')
              // xss protection
              ->withHeader('X-XSS-Protection', '1; mode=block')
              // disable mime sniffing
              ->withHeader('X-Content-Type-Options', 'nosniff')
              // CSP rules
              ->withheader('Content-Security-Policy', "default-src 'self';");
    });

    // $this->app->add(new JwtAuthentication([
    //   "attribute" => "jwt",
    //   "path" => "/",
    //   "algorithm" => getenv('JWT_ALGO'),
    //   "secret" => getenv('JWT_SECRET'),
    //   "error" => function ($request, $response, $arguments) {
    //       return $response->withJson([
    //           'success' => false,
    //           'errors' => "Unauthorized access"
    //       ], 401);
    //   },
    //   "rules" => [
    //     new RequestPathRule([
    //         "path" => "/",
    //         "passthrough" => [
    //           "/login",
    //           "/projects",
    //           "/categories",
    //           "/tags"
    //         ]
    //     ]),
    //     new RequestMethodRule([
    //         "passthrough" => ["GET"]
    //     ]),
    //   ]
    // ]));

    /**
     * Throw 404 for unknown routes instead of 401 from Authentication
     */
    // $this->app->add(function($request, $response, $next) {

    //   $route = $request->getAttribute("route");

    //   if (empty($route)) {
    //     throw new NotFoundException($request, $response);
    //   }

    //   return $next($request, $response);

    // });
  }
}