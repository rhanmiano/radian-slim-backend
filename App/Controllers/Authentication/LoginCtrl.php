<?php

namespace App\Controllers\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Firebase\JWT\JWT;

use App\Controllers\BaseCtrl;
use App\Models\CustomerModel;
use Respect\Validation\Validator as v;
use App\Helpers\UtilityHelper;

class LoginCtrl extends BaseCtrl {

  public function test(Request $request, Response $response, $args) {

    $helper = new UtilityHelper();

    $helper->_hello();

  }

  public function login(Request $request, Response $response, $args) {

    $customerModel = new CustomerModel();

    $bodyArgs = json_decode($request->getBody());

    $validation = $this->validator->validate($bodyArgs, [
      'email'    => v::noWhitespace()->notEmpty()->email(),
      'password' => v::noWhitespace()->notEmpty(),
    ]);

    if ($validation->failed()) {

      $this->retval['status']           = 'failed';
      $this->retval['message']          = VLD_ERR;
      $this->retval['errors']['fields'] = $validation->getErrors();

      $response = $response->withStatus(200)
        ->withHeader('Content-type', 'application/json')
        ->withJson($this->retval);

      return $response;
      die();

    }

    $customer = $customerModel->getCustomerByEmail($bodyArgs->email);

    if(empty($customer[0])) {

      $this->retval['status']  = 'failed';
      $this->retval['message'] = EMAIL_NULL;

      $response = $response->withStatus(200)
        ->withHeader('Content-type', 'application/json')
        ->withJson($this->retval);

      return $response;
      die();

    }

    if(!password_verify($bodyArgs->password, $customer[0]['password'])) {
      $this->retval['status']  = 'failed';
      $this->retval['message'] = PASSWORD_INVLD;

      $response = $response->withStatus(200)
        ->withHeader('Content-type', 'application/json')
        ->withJson($this->retval);

      return $response;
      die();
    }

    $token = $this->tokenCreate($customer[0]);

    $this->retval['status']  = 'success';
    $this->retval['message'] = LOGIN_SUCC;
    $this->retval['token']   = $token['token'];
    $this->retval['expires'] = $token['expires'];

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }

  public function tokenCreate($data) {

    $expires = new \DateTime("+".getenv('JWT_EXPIRES')." minutes"); // token expiration

    $payload = [
      "iat" => (new \DateTime())->getTimeStamp(), // initialized unix timestamp
      "exp" => $expires->getTimeStamp(), // expiration unix timestamp
      "sub" => $data // internal user identifier
    ];

    $token = JWT::encode($payload, getenv('JWT_SECRET') , getenv('JWT_ALGO'));
    
    return [
      'token' => $token,
      'expires' => $expires->getTimestamp()
    ];

  }

}