<?php

require_once 'DatabaseEntity.php';

class User implements DatabaseEntity {
  private $id;
  private $firstName;
  private $lastName;
  private $email;
  private $password;
  private $lastEdit;

  /**
   * User constructor.
   */
  public function __construct() {
    $this->id = null;
  }

  public function __toString() {
    return 'ID: '.$this->id.', First Name: '.$this->firstName.', Last Name: '.$this->lastName;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function setFirstName($firstName) {
    $this->firstName = $firstName;
  }

  public function setLastName($lastName) {
    $this->lastName = $lastName;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  public function setLastEdit($lastEdit) {
    $this->lastEdit = $lastEdit;
  }

  public function getId() {
    return $this->id;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getFirstName() {
    return $this->firstName;
  }

  public function getLastName() {
    return $this->lastName;
  }

  public function getPassword() {
    return $this->password;
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
    if (! isset($input['firstName'])) {
      return false;
    }
    if (! isset($input['lastName'])) {
      return false;
    }
    if (! isset($input['email'])) {
      return false;
    }
    if (! isset($input['password'])) {
      return false;
    }
    return true;
  }

  public static function parse($input) {
    $user = new User();
    if (isset($input['id'])) {
      $user->setId($input['id']);
    }
    if (isset($input['firstName'])) {
      $user->setFirstName($input['firstName']);
    }
    if (isset($input['lastName'])) {
      $user->setLastName($input['lastName']);
    }
    if (isset($input['email'])) {
      $user->setEmail($input['email']);
    }
    if (isset($input['password'])) {
      $user->setPassword($input['password']);
    }
    if (isset($input['lastEdit'])) {
      $user->setLastEdit($input['lastEdit']);
    }
    return $user;
  }
}