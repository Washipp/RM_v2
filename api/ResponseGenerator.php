<?php


class ResponseGenerator {

  private $dbTable;
  private $dbEntity;

  /**
   * UserController constructor.
   * @param DatabaseObject $dbTable
   * @param DatabaseEntity $dbEntity
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