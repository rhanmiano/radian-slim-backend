<?php

namespace App\Models;

use ORM;
use App\Models\BaseModel;

class CustomerModel extends BaseModel {

  public function getAllCustomers() {

    $result = ORM::for_table('customers')->find_array();

    // do not include password in the result
    foreach($result as $key => $value) {
      unset($result[$key]['password']);  
    }

    return $result;

  }

  public function getCustomerById($id) {

    $result = ORM::for_table('customers')
      ->where('id', $id)
      ->find_array();

    unset($result[0]['password']); // do not include password in the result

    return $result;

  }

  public function getCustomerByEmail($email) {

    $result = ORM::for_table('customers')
      ->where('email', $email)
      ->find_array();

    // unset($result[0]['password']); // do not include password in the result

    return $result;

  }

  public function insertCustomer($args) {

    $errors = [];
    // Sample of executing raw query
    $qry1 = "
      INSERT INTO customers 
      (name, password, email, age)
      VALUES (:name, :password, :email, :age) 
    ";

    $qry_params = (array) $args;
    $qry_params['password'] = password_hash($qry_params['password'], PASSWORD_BCRYPT, array('cost' => 12));

    try {

      $result['status'] = ORM::raw_execute($qry1, $qry_params); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    // If ever we need last inserted id
    $id = ORM::get_db()->lastInsertId();

    if (sizeof($errors) > 0) { // with errors
    
      $result['status'] = false;
      $result['errors'] = $errors;

    } else {
      
      return $result;

    }

  }

  public function updateCustomer($id, $args) {

    $customer = ORM::for_table('Customers')
      ->find_one($id);

    foreach ((array) $args as $key => $value) {
      $customer->$key = $value;
    }

    $customer->set_expr('updated_at', 'now()');

    $result = $customer->save();

    return $result;

  }

  public function deleteCustomerById($id) {

    $customer = ORM::for_table('Customers')
      ->find_one($id);

    $result = $customer->delete();

    return $result;

  }

  public function emailExists($email) {

    $result = ORM::for_table('Customers')
      ->where('email', $email)
      ->find_one();

    if (sizeof($result) > 0) {
      
      return true;

    } else {
      
      return false;

    }
  }
}