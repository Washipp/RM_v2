<?php
require_once 'model/UserTable.php';
require_once 'model/User.php';

require_once 'model/Resource.php';
require_once 'model/ResourceTable.php';


require_once 'model/Reservation.php';
require_once 'model/ReservationTable.php';

$user = new User();
$dbUser = new UserTable();

$user->setEmail('teststs@email');
$user->setLastName('Müller');
$user->setFirstName('Paul');
$user->setPassword("asldjfölasjdf");

$dbResource = new ResourceTable();
$resource = new Resource();
$resource->setName('Resource');
$resource->setDescription('Description');


$reservationTable = new ReservationTable();
$startDate = date("Y-m-d H:i:s");
$endDate = date("Y-m-d H:i:s");
$reservation = new Reservation();
$reservation->setEndDate($endDate);
$reservation->setStartDate($startDate);
$reservation->setUserId($user);
$reservation->setResourceId($resource);
$reservation->setComment("comment");

/*
//$read = $reservationTable->read(1);
$read = $reservation;
echo $read.'<br><br>';
$user = new User();
$user = $read->getUser();
echo json_encode($user);

$array = json_encode($read->jsonSerialize());
var_dump($array);
*/

$read = $reservationTable->read(1);
$read->setUserId($user->jsonSerialize());
$read->setResourceId($resource->jsonSerialize());

echo '<br>';
echo json_encode($read->jsonSerialize(), JSON_PRETTY_PRINT);

echo '<br>-----------------------<br>';

//echo 'JSON_ENCODE: '.json_encode($read).'<br>';
//echo 'SERIALIZE: '.json_encode(serialize($read), JSON_PRETTY_PRINT).'<br>';
/*
$read = $objectTable->read($object->getId());

echo 'Object: '.$read.'<br>';

$objectTable->delete($read);

$read = $objectTable->read($object->getId());

echo 'Object: '.$read;


$read = $userTable->read($user->getId());

echo 'User: '.$read.' '.$read->getLastEdit().'<br>';

$userTable->delete($read);

$read = $userTable->read($user->getId());

echo 'User: '.$read.'<br>';*/