<?php

namespace App\Validation;

use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator {

  // protected $errors = array();

  public function validate($fields, array $rules) {

    foreach ($rules as $key => $value) {

      if (!array_key_exists($key, $fields)) {
        $this->errors = "Invalid request body parameters";
        return $this;
        die();

      } else {
        try {

          $value->setName(ucfirst($key))->assert(get_object_vars($fields)[$key]);

        } catch (NestedValidationException $e) {

          $this->errors[$key] = $e->getMessages();

        }
      }

    }

    return $this;

  }

  public function failed() {

    return !empty($this->errors);

  }

  public function getErrors() {

    return $this->errors;

  }

}