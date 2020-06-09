<?php
require_once 'Connection.php';
require_once 'DatabaseObject.php';

class ResourceTable extends Connection implements DatabaseObject {

  /**
   * @param Resource $resource Update, if it exists, this resource or else insert
   * @return Resource|null Resource if transaction was successful, null else
   */
  public function createOrUpdate($resource) {
    if(!($resource instanceof Resource)) {
      //error handling?
      echo 'Tried to update '.$resource.' as a Resource';
      return null;
    } elseif ($resource->getId() == null) { //create new object, since no id has been set
      return $this->createNewResource($resource);
    } else { //update object
      return $this->updateResource($resource);
    }
  }

  /**
   * @param $object Resource to delete
   * @return Resource|null returns deleted Object if successful, else null
   */
  public function delete($object) {
    if (!($object instanceof Resource) || $object->getId() == null) {
      //Error handling?
      echo 'Tried to delete '.$object.' as a resource';
      return null;
    } else {
      return $this->deleteResource($object);
    }
  }

  /**
   * @param $id Resource id of the object to be fetched
   * @return Resource|null Returns null if the user doesn't exist or an error occurred, else return user
   */
  public function read($id) {
    $result = null;
    $stmt = $this->getConnection()->prepare("
            SELECT name, description, last_edit FROM resource WHERE id_O = ?;
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($name, $description, $lastEdit);
    if($stmt->fetch()) {
      $result = new Resource();
      $result->setName($name);
      $result->setDescription($description);
      $result->setLastEdit($lastEdit);
      $result->setId($id);
    }
    $stmt->close();
    return $result;
  }
  /**
   * @return array returns All Resources from the Database as a {@Resources} Object array.
   */
  public function readAll() {
    $array = [];
    $stmt = $this->getConnection()->prepare( "
          SELECT * FROM resource ");
    $stmt->execute();
    $stmt->bind_result($id, $name, $description, $last_edit);
    while($stmt->fetch()){
      $result = new Resource();
      $result->setName($name);
      $result->setDescription($description);
      $result->setLastEdit($last_edit);
      $result->setId($id);
      $array[] = $result;
    }
    $stmt->close();
    return $array;
  }

  private function deleteResource(Resource $resource) {
    $id = $resource->getId();
    $stmt = $this->getConnection()->prepare( "
          DELETE FROM resource WHERE id_o = ?;
    ");
    $stmt->bind_param('i', $id);
    if($stmt->execute()) {
      $stmt->close();
      return $resource;
    } else {
      $stmt->close();
      return null;
    }
  }

  private function createNewResource(Resource $resource) {
    $name = $resource->getName();
    $description = $resource->getDescription();

    $stmt = $this->getConnection()->prepare( "
          INSERT INTO resource (name, description) VALUES (?,?);
    ");
    $stmt->bind_param('ss', $name, $description);

    if($stmt->execute()) {
      $newlyGeneratedId = $this->getConnection()->insert_id;
      $resource->setId($newlyGeneratedId);
      $stmt->close();
      return $resource;
    } else {
      $stmt->close();
      return null;
    }
  }

  private function updateResource(Resource $resource) {
    $id = $resource->getId();
    $name = $resource->getName();
    $description = $resource->getDescription();

    $stmt = $this->getConnection()->prepare("
        UPDATE resource SET name = ?, description = ? WHERE id_o = ?
    ");
    $stmt->bind_param('ssi', $name, $description, $id);

    if($stmt->execute()) {
      $stmt->close();
      return $resource;
    } else {
      $stmt->close();
      return null;
    }
  }


}