<?php


interface DatabaseEntity {
  public function getId();
  public function setId($id);
  public function getLastEdit();
  public function setLastEdit($lastEdit);
  public function jsonSerialize();
  public static function validate($input);
  public static function parse($input);
}