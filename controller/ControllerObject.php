<?php


interface ControllerObject {
  public function get($id);
  public function getAll();
  public function createNew($input);
  public function update($id, $input);
  public function delete($id);
}