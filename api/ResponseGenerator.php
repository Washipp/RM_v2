<?php

/**
 * Class ResponseGenerator
 *
 * This class is used as a interface to perform CRUD Operations on the Database.
 * The provided Objects in the constructor specify which database is going to be used.
 */
class ResponseGenerator {

  private $dbTable;
  private $dbEntity;

  /**
   * UserController constructor.
   * @param DatabaseObject $dbTable used to specify which Database is going to be used.
   * @param DatabaseEntity $dbEntity Object that is mapped into the database.
   */
  public function __construct(DatabaseObject $dbTable, DatabaseEntity $dbEntity) {
    $this->dbTable = $dbTable;
    $this->dbEntity = $dbEntity;
  }

  public function get($id) {
    return $this->dbTable->read($id);
  }

  public function getAll() {
    return $this->dbTable->readAll();
  }

  public function createNew($input) {
    $object = $this->dbEntity->parse($input);
    return $this->dbTable->createOrUpdate($object);
  }

  public function update($id, $input) {
    $object = $this->dbEntity->parse($input);
    $object->setId($id);
    return $this->dbTable->createOrUpdate($object);
  }

  public function delete($id) {
    $object = $this->dbTable->read($id);
    return $this->dbTable->delete($object);
  }

  public function validate($input) {
    return $this->dbEntity->validate($input);
  }
}