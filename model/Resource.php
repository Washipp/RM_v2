<?php

require_once 'DatabaseEntity.php';

class Resource implements DatabaseEntity {
  private $id;
  private $name;
  private $description;
  private $lastEdit;

  /**
   * Resource constructor.
   */
  public function __construct() {
    $this->id = null;
  }


  public function __toString() {
    return 'ID: '.$this->id.', Name: '.$this->name.', Description: '.$this->description;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function setLastEdit($lastEdit) {
    $this->lastEdit = $lastEdit;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getLastEdit() {
    return $this->lastEdit;
  }

  public function jsonSerialize() {
    return get_object_vars($this);
  }

  public static function validate($input) {
    //the following two fields may not be set!
    if (isset($input['id'])) {
      return false;
    }
    if (isset($input['lastEdit'])) {
      return false;
    }

    // the following fields need to be set
    if (! isset($input['name'])) {
      return false;
    }
    if (! isset($input['description'])) {
      return false;
    }
    return true;
  }

  public static function parse($input) {
    $resource = new Resource();
    if (isset($input['id'])) {
      $resource->setId($input['id']);
    }
    if (isset($input['name'])) {
      $resource->setName($input['name']);
    }
    if (isset($input['description'])) {
      $resource->setDescription($input['description']);
    }
    if (isset($input['lastEdit'])) {
      $resource->setLastEdit($input['lastEdit']);
    }
    return $resource;
  }
}