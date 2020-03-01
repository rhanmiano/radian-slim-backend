<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;
use App\Models\CustomerModel;
use Respect\Validation\Validator as v;
use App\Helpers\UtilityHelper;

class CustomerCtrl extends BaseCtrl{
  
  public function test(Request $request, Response $response, $args) {

    $helper = new UtilityHelper();

    $helper->_hello();

  }

  public function all(Request $request, Response $response, $args) {

    $customerModel = new CustomerModel();

    $result = $customerModel->getAllCustomers();

    if (!empty($result)) {

      $this->retval['status']    = 'success';
      $this->retval['message']   = FETCH_SUCC;
      $this->retval['customers'] = $result;

    } else {

      $this->retval['status']    = 'failed';
      $this->retval['message']   = FETCH_EMPTY;
      $this->retval['customers'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }


  public function byId(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $customerModel = new CustomerModel();

    $result = $customerModel->getCustomerById($id);

    if (!empty($result)) {

      $this->retval['status']    = 'success';
      $this->retval['message']   = FETCH_SUCC;
      $this->retval['customer'] = $result[0];

    } else {

      $this->retval['status']    = 'failed';
      $this->retval['message']   = FETCH_EMPTY;
      $this->retval['customer'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }

  public function create(Request $request, Response $response, $args) {

    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'name'     => v::notEmpty()->alpha(),
      'password' => v::noWhitespace()->notEmpty(),
      'email'    => v::noWhitespace()->notEmpty()->email(),
      'age'      => v::noWhitespace()->notEmpty()->numeric()
    ]);

    if ($validation->failed()) {

      $this->retval['status']           = 'failed';
      $this->retval['message']          = VLD_ERR;
      $this->retval['errors']['fields'] = $validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

      return $response;

    }

    $customerModel = new CustomerModel();

    $result = $customerModel->insertCustomer($body_args);

    if ($result['status']) {

      $this->retval['status']  = 'success';
      $this->retval['message'] = CREATE_SUCC;

    } else {

      $this->retval['status']  = 'failed';
      $this->retval['message'] = CREATE_ERR;
      $this->retval['errors'] = $result['errors'];

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }

  public function update(Request $request, Response $response, $args) {

    $id = (int)$args['id'];
    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()->alpha(),
      'email' => v::noWhitespace()->notEmpty()->email(),
      'age' => v::noWhitespace()->notEmpty()->numeric()
    ]);

    if ($validation->failed()) {

      $this->retval['status']           = 'failed';
      $this->retval['message']          = VLD_ERR;
      $this->retval['errors']['fields'] = $validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

      return $response;

    }

    $customerModel = new CustomerModel();

    $result = $customerModel->updateCustomer($id, $body_args);

    if ($result) {

      $this->retval['status']  = 'success';
      $this->retval['message'] = UPDATE_SUCC;

    } else {

      $this->retval['status']  = 'failed';
      $this->retval['message'] = UPDATE_ERR;

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }

  public function delete(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $customerModel = new CustomerModel();

    $result = $customerModel->deleteCustomerById($id);

    if ($result) {

      $this->retval['status']  = 'success';
      $this->retval['message'] = DELETE_SUCC;

    } else {

      $this->retval['status']  = 'failed';
      $this->retval['message'] = DELETE_ERR;

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }

}