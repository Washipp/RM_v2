<?php

require_once 'DatabaseEntity.php';

class Reservation implements DatabaseEntity {
  private $id;
  private $userId;
  private $resourceId;
  private $comment;
  private $startDate;
  private $endDate;
  private $lastEdit;

  /**
   * Reservation constructor.
   */
  public function __construct() {
    $this->id = null;
  }

  public function __toString() {
   return 'ID: '.$this->id.', ResourceId: '.$this->resourceId.', UserId: '.$this->userId.', Comment: '.$this->comment;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function setResourceId($resourceId) {
    $this->resourceId = $resourceId;
  }

  public function setComment($comment) {
    $this->comment = $comment;
  }

  public function setStartDate($startDate) {
    $this->startDate = $startDate;
  }

  public function setEndDate($endDate) {
    $this->endDate = $endDate;
  }

  public function setLastEdit($lastEdit) {
    $this->lastEdit = $lastEdit;
  }

  public function getId() {
    return $this->id;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getResourceId() {
    return $this->resourceId;
  }

  public function getComment() {
    return $this->comment;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function getEndDate() {
    return $this->endDate;
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
    if (! isset($input['userId'])) {
      return false;
    }
    if (! isset($input['resourceId'])) {
      return false;
    }
    if (! isset($input['comment'])) {
      return false;
    }
    if (! isset($input['startDate'])) {
      return false;
    }
    if (! isset($input['endDate'])) {
      return false;
    }
    return true;
  }

  public static function parse($input) {
    $reservation = new Reservation();
    if (isset($input['id'])) {
      $reservation->setId($input['id']);
    }
    if (isset($input['userId'])) {
      $reservation->setUserId($input['userId']);
    }
    if (isset($input['resourceId'])) {
      $reservation->setResourceId($input['resourceId']);
    }
    if (isset($input['comment'])) {
      $reservation->setComment($input['comment']);
    }
    if (isset($input['startDate'])) {
      $reservation->setStartDate($input['startDate']);
    }
    if (isset($input['endDate'])) {
      $reservation->setEndDate($input['endDate']);
    }
    if (isset($input['lastEdit'])) {
      $reservation->setLastEdit($input['lastEdit']);
    }
    return $reservation;
  }
}