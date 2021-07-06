<?php

namespace App\Controllers\Main;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;
use App\Models\Main\ProjectModel;

use Respect\Validation\Validator as v;

use App\Helpers\UtilityHelper;

class ProjectCtrl extends BaseCtrl{
  
  protected $projectModel;

  public function __construct($app) {
    
    parent::__construct($app);

    $this->projectModel = new ProjectModel();
  
  }

  public function all(Request $request, Response $response, $args) {

    $result = $this->projectModel->getAllProjects();

    if (!empty($result)) {

      $this->res['success']  = true;
      $this->res['message']  = FETCH_SUCC;
      $this->res['projects'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']  = false;
      $this->res['message']  = FETCH_EMPTY;
      $this->res['projects'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }


  public function byId(Request $request, Response $response, $args) {

    $id = $this->crypto->_decrypt($args['id']);

    $result = $this->projectModel->getProjectById($id);

    if (!empty($result)) {

      $this->res['success'] = true;
      $this->res['message'] = FETCH_SUCC;
      $this->res['project'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']    = false;
      $this->res['message']   = FETCH_EMPTY;
      $this->res['project'] = [];

    }

    return $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

  }

  public function allProjectTags(Request $request, Response $response, $args) {

    $result = $this->projectModel->getAllProjectTags();

    if (!empty($result)) {

      $this->res['success']  = true;
      $this->res['message']  = FETCH_SUCC;
      $this->res['tags'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']  = false;
      $this->res['message']  = FETCH_EMPTY;
      $this->res['tags'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function projectTagsById(Request $request, Response $response, $args) {

    $id = $this->crypto->_decrypt($args['id']);

    $result = $this->projectModel->getProjectTagsById($id);

    if (!empty($result)) {

      $this->res['success'] = true;
      $this->res['message'] = FETCH_SUCC;
      $this->res['project'] = $this->crypto->_cryptIds($result, '_encrypt');

    } else {

      $this->res['success']    = false;
      $this->res['message']   = FETCH_EMPTY;
      $this->res['project'] = [];

    }

    return $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

  }

  public function projectTagCreate(Request $request, Response $response, $args) {
    $body_args = json_decode($request->getBody());

    // Validate input
    $validation = $this->validator->validate($body_args, [
      'project_id' => v::notEmpty()->alnum(),
      'tag_id'     => v::notEmpty()->alnum(),
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

    // Sanitize input
    $body_args = UtilityHelper::_sanitize_array($body_args, null, true);

    $body_args = $this->crypto->_cryptIds($body_args, '_decrypt', true);
    $result = $this->projectModel->insertProjectTag($body_args);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = CREATE_SUCC;

    } else {

      $this->res['success'] = false;
      $this->res['message'] = isset($result['message']) ? $result['message'] : CREATE_ERR;

      if (isset($result['errors']))
        $this->res['errors']  = $result['errors'] ? $result['errors'] : null;

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function create(Request $request, Response $response, $args) {

    $body_args = json_decode($request->getBody());
    // Validate input
    $validation = $this->validator->validate($body_args, [
      'category_id'       => v::notEmpty()->alnum(),
      'name'              => v::notEmpty()->alnum(),
      'description'       => v::notEmpty()->regex(UtilityHelper::_regex_keyboard_symbols()),
      'short_description' => v::notEmpty()->regex(UtilityHelper::_regex_keyboard_symbols()),
      'tech'              => v::notEmpty()->alpha(),
      'img_url'           => v::optional(v::url()),
      'project_url'       => v::optional(v::url()),
      'date_from'         => v::notEmpty()->date(),
      'date_end'          => v::notEmpty()->date(),
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

    // Sanitize input
    $body_args = UtilityHelper::_sanitize_array($body_args, null, true);

    $body_args->category_id = $this->crypto->_decrypt($body_args->category_id);
    $result = $this->projectModel->insertProject($body_args);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = CREATE_SUCC;

    } else {

      $this->res['success'] = false;
      $this->res['message'] = isset($result['message']) ? $result['message'] : CREATE_ERR;

      if (isset($result['errors']))
        $this->res['errors']  = $result['errors'] ? $result['errors'] : null;

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function update(Request $request, Response $response, $args) {

    $id = $this->crypto->_decrypt($args['id']);
    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'category_id'       => v::notEmpty()->alnum(),
      'name'              => v::notEmpty()->alnum(),
      'description'       => v::notEmpty()->regex(UtilityHelper::_regex_keyboard_symbols()),
      'short_description' => v::notEmpty()->regex(UtilityHelper::_regex_keyboard_symbols()),
      'tech'              => v::notEmpty()->alpha(),
      'img_url'           => v::optional(v::url()),
      'project_url'       => v::optional(v::url()),
      'date_from'         => v::notEmpty()->date(),
      'date_end'          => v::notEmpty()->date(),
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

    // Sanitize input
    $body_args = UtilityHelper::_sanitize_array($body_args, null, true);
    
    $body_args->category_id = $this->crypto->_decrypt($body_args->category_id);
    $result = $this->projectModel->updateProject($id, $body_args);

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

    $id = $this->crypto->_decrypt($args['id']);;

    $result = $this->projectModel->archiveProjectById($id);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = UPDATE_SUCC;

    } else {

      $this->res['message'] = isset($result['message']) ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors']))
        $this->res['errors'] = isset($result['errors']) ? $result['errors'] : null;

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function restore(Request $request, Response $response, $args) {

    $id = $this->crypto->_decrypt($args['id']);;

    $result = $this->projectModel->restoreProjectById($id);

    if ($result['qry_status']) {

      $this->res['success']  = true;
      $this->res['message'] = UPDATE_SUCC;

    } else {

      $this->res['message'] = isset($result['message']) ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors'])) 
        $this->res['errors'] = $result['errors'] ? $result['errors'] : null;

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function delete(Request $request, Response $response, $args) {

    $id = $this->crypto->_decrypt($args['id']);;

    $result = $this->projectModel->deleteProjectById($id);

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