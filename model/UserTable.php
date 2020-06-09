<?php
require_once 'Connection.php';
require_once 'DatabaseObject.php';
require_once 'User.php';

class UserTable extends Connection implements DatabaseObject {

  /**
   * @param User $resource {@User} to update or insert
   * @return User|null User object if transaction was successful, null else
   */
  public function createOrUpdate($resource) {
    if (!($resource instanceof User)) {
      //Error handling?
      echo 'Tried to update '.$resource.' as a user';
      return null;
    } elseif ($resource->getId() == null) { //create new user, no id has been set
      return $this->createNewUser($resource);
    } else { //update user
      return $this->updateUser($resource);
    }
  }

  /**
   * @param User $object user object to delete
   * @return bool returns true if deleting was successful, else false
   */
  public function delete($object) {
    if (!($object instanceof User) || $object->getId() == null) {
      //Error handling?
      echo 'Tried to delete '.$object.' as a user';
      return false;
    } else {
      return $this->deleteUser($object);
    }
  }

  /**
   * @param $id User id of the user to be fetched
   * @return User|null Returns null if the user has not been found or an error occurred, else returns user without the password
   */
  public function read($id) {
    $result = null;
    $stmt = $this->getConnection()->prepare("
            SELECT first_name, last_name, email, last_edit FROM user WHERE id_u = ?;
        ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName, $email, $lastEdit);
    if($stmt->fetch()) {
      $result = new User();
      $result->setFirstName($firstName);
      $result->setLastName($lastName);
      $result->setEmail($email);
      $result->setLastEdit($lastEdit);
      $result->setId($id);
    }
    $stmt->close();
    return $result;
  }

  /**
   * @return array returns All Users from the Database as a {@User} Object array.
   */
  public function readAll() {
    $array = [];
    $stmt = $this->getConnection()->prepare( "
          SELECT * FROM user
        ");
    $stmt->execute();
    $stmt->bind_result($id, $first_name, $last_name, $email, $password, $last_edit);
    while($stmt->fetch()){
      $result = new User();
      $result->setId($id);
      $result->setFirstName($first_name);
      $result->setLastName($last_name);
      $result->setEmail($email);
      $result->setLastEdit($last_edit);
      $array[] = $result;
    }
    $stmt->close();
    return $array;
  }

  /**
   * @param User $user user to delete
   * @return bool returns true if deleting was successful, else false
   */
  private function deleteUser(User $user) {

    $id = $user->getId();
    $stmt = $this->getConnection()->prepare( "
          DELETE FROM user WHERE id_u = ?;
        ");
    $stmt->bind_param('i', $id);
    if($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      echo $stmt->error;
      $stmt->close();
      return false;
    }
  }

  /**
   * @param $user user to update
   * @return User|null returns null if updated failed, else returns the updated User
   */
  private function createNewUser(User $user) {
    $firstName = $user->getFirstName();
    $lastName = $user->getLastName();
    $email = $user->getEmail();
    $password = $user->getPassword();
    $stmt = $this->getConnection()->prepare( "
          INSERT INTO user (first_name, last_name, email, password) VALUES (?,?,?,?);
        ");
    $stmt->bind_param('ssss', $firstName, $lastName, $email, $password);

    if($stmt->execute()) {
      $newlyGeneratedId = $this->getConnection()->insert_id;
      $user->setId($newlyGeneratedId);
      $stmt->close();
      return $user;
    } else {
      $stmt->close();
      return null;
    }
  }

  /**
   * @param $user user to update
   * @return User|null returns null if updated failed, else returns the updated User
   */
  private function updateUser(User $user) {
    $id = $user->getId();
    $firstName = $user->getFirstName();
    $lastName = $user->getLastName();
    $email = $user->getEmail();
    $password = $user->getPassword();

    $stmt = $this->getConnection()->prepare("
            UPDATE user SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id_u = ?
        ");
    $stmt->bind_param('ssssi', $firstName, $lastName, $email, $password, $id);

    if($stmt->execute()) {
      $stmt->close();
      return $user;
    } else {
      $stmt->close();
      return null;
    }
  }

}