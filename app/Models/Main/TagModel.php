<?php

namespace App\Models\Main;

use ORM;
use App\Models\BaseModel;
use App\Helpers\DateHelper;

class TagModel extends BaseModel {

  public function getAllTags() {

    $result = ORM::for_table('tag')->find_array();

    return $result;
  }

  public function getTagById($id) {

    $result = ORM::for_table('tag')
      ->where('id', $id)
      ->find_array();


    return $result;

  }

  public function insertTag($args) {

    $errors = [];
    // Sample of executing raw query
    $qry1 = "INSERT INTO Tag
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

  public function updateTag($id, $args) {

    $errors = [];

    $tag = ORM::for_table('tag')
      ->find_one($id);

    $tag_data = ORM::for_table('tag')
      ->where('id', $id)
      ->find_array();

    // Columns to be updated
    $columns = array_keys($tag_data[0]);

    $updated_columns = [];

    foreach($columns as $column){
      if(isset($args->$column) && $tag->$column != $args->$column) {        
        $tag->$column = $args->$column;
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
      $tag->$key = $value;
    }

    $tag->set('date_updated', DateHelper::_now());

    try {

      $result['qry_status'] = $tag->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

     return $result;

  }

  public function archiveTagById($id) {

    $errors = [];

    $tag = ORM::for_table('tag')
      ->find_one($id);

    $tag->set([
      'date_deleted' => DateHelper::_now(),
      'is_deleted' => 1
    ]);

    try {

      $result['qry_status'] = $tag->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function restoreTagById($id) {

    $errors = [];

    $tag = ORM::for_table('tag')
      ->find_one($id);

    $tag->set([
      'date_updated' => null,
      'date_deleted' => null,
      'is_deleted' => 0
    ]);

    try {

      $result['qry_status'] = $tag->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function deleteTagById($id) {

    $tag = ORM::for_table('tag')
      ->find_one($id);

    $result['qry_status'] = $tag ? $tag->delete() : $tag;

    return $result;

  }

}