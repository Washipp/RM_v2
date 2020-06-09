<?php

/**
 * Interface DatabaseObject
 * Interface for all savable objects
 */
interface DatabaseObject {
  public function createOrUpdate($resource);
  public function delete($object);
  public function read($id);
  public function readAll();
}