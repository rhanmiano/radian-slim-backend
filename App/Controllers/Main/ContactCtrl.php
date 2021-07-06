<?php

namespace App\Controllers\Main;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;

use Respect\Validation\Validator as v;

use App\Helpers\UtilityHelper;
use App\Helpers\EmailHelper;

class ContactCtrl extends BaseCtrl{

  public function __construct($app) {

    parent::__construct($app);    

  }

  public function saveMessage(Request $request, Response $response, $args) {

    // data sent by formdata or application/x-www-form-urlencoded
    // could be get by getParsedBody
    $body_args = $request->getParsedBody();

    // Validate input
    $validation = $this->validator->validate((object) $body_args, [
      'category'  => v::notEmpty()->alnum(),
      'nickname'  => v::notEmpty()->alnum(),
      'email'     => v::notEmpty()->email(),
      'contactNo' => v::optional(v::stringType()->digit()->length(null, 15))->setName('Contact No'),
      'message'   => v::notEmpty()->alnum('. , ( ) / ? ! \' "')
    ]);

    if ($validation->failed()) {
      $this->res['success'] = false;
      $this->res['message'] = VLD_ERR;
      $this->res['errors']  = (array)$validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

      return $response;
    }

    // Sanitize input
    $body_args = UtilityHelper::_sanitize_array($body_args, null, true);
    $body_args = $this->crypto->_cryptIds($body_args, '_decrypt', true);

    switch ($body_args->category) {
      case '1':
        $body_args->category = 'General Inquiry';
        break;
      case '2':
        $body_args->category = 'Development';
        break;
      case '3':
        $body_args->category = 'Design';
        break;
      default:
        break;
    }
    $message = "<h3>" . $body_args->category ."</h3>" . 
               "<p><strong>Nickname: </strong>" . $body_args->nickname . "</p>" . 
               "<p><strong>Email: </strong>" . $body_args->email . "</p>" . 
               "<p><strong>Contact No: </strong>" . $body_args->contactNo . "</p>" .
               "<p><strong>Mesasge: </strong></p>" .
               "<p>" . $body_args->message . "</p>";
    
    $this->res['dump'] = $message;
    $data = array(
      'from_email'      => $body_args->email,
      'from_email_name' => $body_args->nickname,
      'to_email'        => 'rhanmiano29@gmail.com',
      'to_email_name'   => 'Jan Ray Miano',
      'subject'         => "Radian Contact Form",
      'message'         => $message
    );

    $email = new EmailHelper($data);
    $email = $email->get();
    
    if (!$email->send()) {
      $this->res['success'] = false;
      $this->res['message'] = EMAIL_NOT_SENT;
      $this->res['errors']  = 'Mailer Error: ' . $email->ErrorInfo;
      
    } else {
      $this->res['success'] = true;
      $this->res['message'] = 'Message has been sent!';
    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;
    
  }

}