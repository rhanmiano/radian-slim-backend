<?php

namespace App\Controllers\Main;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;
use App\Models\Main\CategoryModel;
use Respect\Validation\Validator as v;


class CategoryCtrl extends BaseCtrl{
  
  protected $categoryModel;

  public function __construct($app) {
    
    parent::__construct($app);

    $this->categoryModel = new CategoryModel();
  
  }

  public function all(Request $request, Response $response, $args) {

    $result = $this->categoryModel->getAllCategories();

    if (!empty($result)) {

      $this->res['success']    = true;
      $this->res['message']    = FETCH_SUCC;
      $this->res['categories'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']    = false;
      $this->res['message']    = FETCH_EMPTY;
      $this->res['categories'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }


  public function byId(Request $request, Response $response, $args) {

    $id = $args['id'];

    $result = $this->categoryModel->getCategoryById($id);

    if (!empty($result)) {

      $this->res['success']  = true;
      $this->res['message']  = FETCH_SUCC;
      $this->res['category'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']  = false;
      $this->res['message']  = FETCH_EMPTY;
      $this->res['category'] = [];

    }

    return $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

  }

  public function create(Request $request, Response $response, $args) {

    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()->alnum(),
    ]);

    if ($validation->failed()) {
    
      $this->res['success'] = false;
      $this->res['message'] = VLD_ERR;
      $this->res['errors']  = $validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

      return $response;

    }

    $result = $this->categoryModel->insertCategory($body_args);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = CREATE_SUCC;

    } else {

      $this->res['success'] = false;
      $this->res['message'] = $result['message'] ? $result['message'] : CREATE_ERR;

      if (isset($result['errors']))
        $this->res['errors']  = $result['errors'] ? $result['errors'] : null;

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function update(Request $request, Response $response, $args) {

    $id = (int)$args['id'];
    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()->alnum(),
    ]);

    if ($validation->failed()) {

      $this->res['success'] = false;
      $this->res['message'] = VLD_ERR;
      $this->res['errors']  = $validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

      return $response;
    }

    $result = $this->categoryModel->updateCategory($id, $body_args);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = UPDATE_SUCC;

    } else {

      $this->res['message'] = $result['message'] ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors']))
        $this->res['errors'] = $result['errors'] ? $result['errors'] : null;

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function archive(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $result = $this->categoryModel->archiveCategoryById($id);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = UPDATE_SUCC;

    } else {

      $this->res['message'] = $result['message'] ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors'])) {

        $this->res['errors'] = $result['errors'] ? $result['errors'] : null;

      }

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function restore(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $result = $this->categoryModel->restoreCategoryById($id);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = UPDATE_SUCC;

    } else {

      $this->res['message'] = $result['message'] ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors'])) {

        $this->res['errors'] = $result['errors'] ? $result['errors'] : null;

      }

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function delete(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $result = $this->categoryModel->deleteCategoryById($id);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = DELETE_SUCC;

    } else {

      $this->res['success']  = false;
      $this->res['message'] = DELETE_ERR;

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

}