<?php

namespace App\Models\Main;

use ORM;
use App\Models\BaseModel;
use App\Helpers\DateHelper;

class ProjectModel extends BaseModel {

  public function getAllProjects() {

    $result = ORM::for_table('project')->find_array();

    return $result;

  }

  public function getProjectById($id) {

    $result = ORM::for_table('project')
      ->where('id', $id)
      ->find_array();

    return $result;

  }

  public function getAllProjectTags() {

    $result = ORM::for_table('project_tag')
      ->select_many('project_tag.id', 'project_id', 'tag_id', array('tag_name' => 'name'))
      ->join('tag', array('project_tag.tag_id', '=', 'tag.id'))
      ->find_array();

    return $result;

  }

  public function getProjectTagsById($id) {

    $result = ORM::for_table('project_tag')
      ->select_many('project_tag.id', 'project_id', 'tag_id', array('tag_name' => 'name'))
      ->join('tag', array('project_tag.tag_id', '=', 'tag.id'))
      ->where('project_id', $id)
      ->find_array();

    return $result;

  }

  public function insertProjectTag($args) {
    $errors = [];

    $project_tag = ORM::for_table('project_tag')->create();
    $project_tag->set([
      'project_id'   => $args->project_id,
      'tag_id'       => $args->tag_id,
      'date_created' => DateHelper::_now()
    ]);

    try {

      $result['qry_status'] = $project_tag->save(); 

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

  public function insertProject($args) {

    $errors = [];

    // Sample of executing raw query
    $qry1 = "INSERT INTO Project
              (
                category_id,
                name,
                description,
                short_description,
                img_url,
                project_url,
                date_from,
                date_end,
                date_created
              )
             VALUES 
              (
                :category_id,
                :name,
                :description,
                :short_description,
                :img_url,
                :project_url,
                :date_from,
                :date_end,
                :date_created
              ) ";

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

  public function updateProject($id, $args) {
    $errors = [];

    $project = ORM::for_table('project')
      ->find_one($id);

    $project_data = ORM::for_table('project')
      ->where('id', $id)
      ->find_array();

    if (!$project || !$project_data) {
        $result['qry_status'] = false;
        $result['message'] = FETCH_EMPTY;
        
        return $result;
    }

    // Columns to be updated
    $columns = array_keys($project_data[0]);

    $updated_columns = [];

    foreach($columns as $column){
      if(isset($args->$column) && $project->$column != $args->$column) {        
        $project->$column = $args->$column;
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
      $project->$key = $value;
    }

    $project->set('date_updated', DateHelper::_now());

    try {

      $result['qry_status'] = $project->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function archiveProjectById($id) {

    $errors = [];

    $project = ORM::for_table('project')
      ->find_one($id);

    if (!$project) {

      $result['qry_status'] = false;
      $result['message'] = FETCH_EMPTY;
      
      return $result;
    }

    $project->set([
      'date_deleted' => DateHelper::_now(),
      'is_deleted' => 1
    ]);

    try {

      $result['qry_status'] = $project->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function restoreProjectById($id) {

    $errors = [];

    $project = ORM::for_table('project')
      ->find_one($id);

    if (!$project) {
      $result['qry_status'] = false;
      $result['message'] = FETCH_EMPTY;
      
      return $result;
    }

    $project->set([
      'date_updated' => null,
      'date_deleted' => null,
      'is_deleted' => 0
    ]);

    try {

      $result['qry_status'] = $project->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function deleteProjectById($id) {

    $project = ORM::for_table('project')
      ->find_one($id);
  
    // if no project, project will be false
    $result['qry_status'] = $project ? $project->delete() : $project;
    
    return $result;

  }

  public function addProjectTag($args) {
    
    $errors = [];

    $projectTag = ORM::for_table('project_tag')->create();

    $projectTag->set([
      "project_id" => $args->project_id,
      "tag_id" => $args->tag_id,
      "date_created" => DateHelper::_now()
    ]);

    try {

      $result['qry_status'] = $projectTag->save();; 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

}