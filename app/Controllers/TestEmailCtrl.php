<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;

use Respect\Validation\Validator as v;

use App\Helpers\EmailHelper;

class TestEmailCtrl extends BaseCtrl{

  protected $mail;

  public function __construct($app) {

    parent::__construct($app);

    $data = array(
      'from_email' => 'rhanmiano29@gmail.com',
      'from_email_name'=> 'Rhan Miano',
      'to_email' => 'jmiano.cloudpanda@gmail.com',
      'to_email_name' => 'Jan Ray Miano',
      'subject' => 'Slim PHP Test Send Mail'
    );

    $this->mail = new EmailHelper($data);
  }

  public function testEmail(Request $request, Response $response, $args) {

    $email = $this->mail->get();

    if (!$email->send()) {
      echo 'Mailer Error: '. $email->ErrorInfo;
    } else {
      echo 'Email sent!';
    }
    
  }

}