<?php

namespace App\Models\Main;

use ORM;
use App\Models\BaseModel;
use App\Helpers\DateHelper;

class CategoryModel extends BaseModel {

  public function getAllCategories() {

    $result = ORM::for_table('category')->find_array();

    return $result;
  }

  public function getCategoryById($id) {

    $result = ORM::for_table('category')
      ->where('id', $id)
      ->find_array();

    return $result;

  }

  public function insertCategory($args) {

    $errors = [];

    // Sample of executing raw query
    $qry1 = "INSERT INTO Category
            (name, date_created)
            VALUES (:name, :date_created) ";

    $bind_data = (array) $args;
    $bind_data['date_created'] = DateHelper::_now();

    try {

      $result['qry_status'] = ORM::raw_execute($qry1, $bind_data); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    // If ever we need last inserted id
    $id = ORM::get_db()->lastInsertId();

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function updateCategory($id, $args) {
    $errors = [];

    $category = ORM::for_table('category')
      ->find_one($id);

    $category_data = ORM::for_table('category')
      ->where('id', $id)
      ->find_array();

    // Columns to be updated
    $columns = array_keys($category_data[0]);

    $updated_columns = [];

    foreach($columns as $column){
      if(isset($args->$column) && $category->$column != $args->$column) {        
        $category->$column = $args->$column;
        array_push($updated_columns, $column);
      }
    }

    // Do not go through if nothing to be changed
    if (sizeof($updated_columns) == 0) {
      $result['qry_status'] = false;
      $result['message'] = UPDATE_EMPTY;

      return $result;
      die();
    }

    foreach ((array) $args as $key => $value) {
      $category->$key = $value;
    }

    $category->set('date_updated', DateHelper::_now());

    try {

      $result['qry_status'] = $category->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function archiveCategoryById($id) {

    $errors = [];

    $category = ORM::for_table('category')
      ->find_one($id);

    $category->set([
      'date_deleted' => DateHelper::_now(),
      'is_deleted' => 1
    ]);

    try {

      $result['qry_status'] = $category->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function restoreCategoryById($id) {

    $errors = [];

    $category = ORM::for_table('category')
      ->find_one($id);

    $category->set([
      'date_updated' => null,
      'date_deleted' => null,
      'is_deleted' => 0
    ]);

    try {

      $result['qry_status'] = $category->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function deleteCategoryById($id) {

    $category = ORM::for_table('category')
      ->find_one($id);
  
    // if no category, category will be false
    $result['qry_status'] = $category ? $category->delete() : $category;
    
    return $result;

  }
}