<?php

namespace App\Controllers\Main;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;
use App\Models\Main\TagModel;
use Respect\Validation\Validator as v;


class TagCtrl extends BaseCtrl{

  private $tagModel;

  public function __construct($app) {

    parent::__construct($app);

    $this->tagModel = new TagModel;

  }

  public function all(Request $request, Response $response, $args) {

    $result = $this->tagModel->getAllTags();

    if (!empty($result)) {

      $this->res['success'] = true;
      $this->res['message'] = FETCH_SUCC;
      $this->res['tags']    = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']    = false;
      $this->res['message']    = FETCH_EMPTY;
      $this->res['tags'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }


  public function byId(Request $request, Response $response, $args) {

    $id = $args['id'];

    $result = $this->tagModel->getTagById($id);

    if (!empty($result)) {

      $this->res['success']    = true;
      $this->res['message']   = FETCH_SUCC;
      $this->res['tag'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']    = false;
      $this->res['message']   = FETCH_EMPTY;
      $this->res['tag'] = [];

    }

    return $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);;

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

    $result = $this->tagModel->insertTag($body_args);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = CREATE_SUCC;

    } else {

      $this->res['success'] = false;
      $this->res['message'] = $result['message'] ? $result['message'] : CREATE_ERR;
      $this->res['errors']  = $result['errors'] ? $result['errors'] : $result['errors'];

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

    $result = $this->tagModel->updateTag($id, $body_args);

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

  public function archive(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $result = $this->tagModel->archiveTagById($id);

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

    $result = $this->tagModel->restoreTagById($id);

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

    $result = $this->tagModel->deleteTagById($id);

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