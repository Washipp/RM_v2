<?php
require_once 'Connection.php';
require_once 'DatabaseObject.php';

class ReservationTable extends Connection implements DatabaseObject {

  /**
   * @param Reservation $resource {@Reservation} to update or insert if no id is provided inside the object
   * @return Reservation|null Reservation if transaction was successful, else null
   */
  public function createOrUpdate($resource) {
    if(!($resource instanceof Reservation)) {
      //TODO: Error Handling
      echo 'Tried to update '.$resource.' as a Reservation';
      return null;
    } elseif ($resource->getId() == null) {
      return $this->createNewReservation($resource);
    } else {
      return $this->updateReservation($resource);
    }
  }

  /**
   * @param $object Reservation to delete
   * @return Reservation|null returns deleted Reservation if successful, else null
   */
  public function delete($object) {
    if(!($object instanceof Reservation) || $object->getId() == null) {
      // Error handling
      echo 'Tried to delete '.$object.' as a Reservation';
      return null;
    } else {
      return $this->deleteReservation($object);
    }
  }

  /**
   * @param $id Reservation-id of the object to be fetched
   * @return Reservation|null Returns null if the reservation doesnt exist or an error occurred, else return reservation
   */
  public function read($id) {
    $result = null;

    $stmt = $this->getConnection()->prepare("
            SELECT u_id, o_id, comment, start_date, end_date, last_edit FROM reservation WHERE id_r = ?;
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($u_id, $o_id, $comment, $start_date, $end_date, $lastEdit);

    if ($stmt->fetch()) {
      $result = new Reservation();
      $result->setUserId($u_id);
      $result->setResourceId($o_id);
      $result->setComment($comment);
      $result->setStartDate($start_date);
      $result->setEndDate($end_date);
      $result->setLastEdit($lastEdit);
      $result->setId($id);
    }
    $stmt->close();
    return $result;
  }

  /**
   * @return array returns All Reservations from the Database as a {@Reservation} Object array.
   */
  public function readAll() {
    $array = [];
    $stmt = $this->getConnection()->prepare( "
          SELECT * FROM reservation ");
    $stmt->execute();
    $stmt->bind_result($id_r, $id_o, $id_u, $comment, $start_date, $end_date, $last_edit);
    while($stmt->fetch()){
      $result = new Reservation();
      $result->setId($id_r);
      $result->setUserId($id_u);
      $result->setResourceId($id_o);
      $result->setComment($comment);
      $result->setStartDate($start_date);
      $result->setEndDate($end_date);
      $result->setLastEdit($last_edit);

      $array[] = $result;
    }
    $stmt->close();
    return $array;
  }

  private function deleteReservation(Reservation $reservation) {
    $id = $reservation->getId();
    $stmt = $this->getConnection()->prepare( "
          DELETE FROM reservation WHERE id_r = ?;
        ");
    $stmt->bind_param('i', $id);
    if($stmt->execute()) {
      $stmt->close();
      return $reservation;
    } else {
      $stmt->close();
      return null;
    }
  }

  private function createNewReservation(Reservation $reservation) {
    $userId = $reservation->getUserId();
    $resourceId = $reservation->getResourceId();
    $comment = $reservation->getComment();
    $startDate = $reservation->getStartDate();
    $endDate = $reservation->getEndDate();
    $stmt = $this->getConnection()->prepare( "
          INSERT INTO reservation (u_id, o_id, comment, start_date, end_date) VALUES (?,?,?,?,?);
    ");
    $stmt->bind_param('iisss', $userId, $resourceId, $comment, $startDate, $endDate);
    if($stmt->execute()) {
      $newlyGeneratedId = $this->getConnection()->insert_id;
      $reservation->setId($newlyGeneratedId);
      $stmt->close();
      return $reservation;
    } else {
      $stmt->close();
      return null;
    }
  }

  /**
   * @param Reservation $reservation to update
   * @return Reservation|null
   */
  private function updateReservation(Reservation $reservation) {
    $id = $reservation->getId();
    $userId = $reservation->getUserId();
    $resourceId = $reservation->getResourceId();
    $comment = $reservation->getComment();
    $startDate = $reservation->getStartDate();
    $endDate = $reservation->getEndDate();
    $stmt = $this->getConnection()->prepare("
        UPDATE reservation SET u_id = ?, o_id = ?, comment = ?, start_date = ?, end_date = ? WHERE id_r = ?
    ");
    $stmt->bind_param('iisssi', $userId, $resourceId, $comment, $startDate, $endDate, $id);

    if($stmt->execute()) {
      $stmt->close();
      return $reservation;
    } else {
      $stmt->close();
      return null;
    }
  }
}