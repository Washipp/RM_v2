<?php
require_once 'APIRequestController.php';
require_once 'ResponseGenerator.php';

require_once '../model/UserTable.php';
require_once '../model/User.php';
require_once '../model/ResourceTable.php';
require_once '../model/Resource.php';
require_once '../model/ReservationTable.php';
require_once '../model/Reservation.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$uriPosition = 3; //TODO: Change search for api in url

if ($uri[$uriPosition] !== 'user' && $uri[$uriPosition] !== 'reservation' && $uri[$uriPosition] !== 'resource') {
  header("HTTP/1.1 404 Not Found");
  exit();
}

$id = null;
if (isset($uri[$uriPosition + 1])) {
  $id = (int) $uri[$uriPosition + 1];
}

$requestMethod = $_SERVER["REQUEST_METHOD"]; // is either POST, GET, PUT, DELETE

$dbTable = null;
$entity = null;

switch ($uri[$uriPosition]) {
  case 'user':
    $dbTable = new UserTable();
    $entity = new User();
    break;
  case 'resource':
    $dbTable = new ResourceTable();
    $entity = new Resource();
    break;
  case 'reservation':
    $dbTable = new ReservationTable();
    $entity = new Reservation();
    break;
  default:
    break;
}
$controller = new APIRequestController($id, $requestMethod, new ResponseGenerator($dbTable, $entity));
$controller->processRequest();