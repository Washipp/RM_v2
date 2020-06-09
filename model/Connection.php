<?php


abstract class Connection {
  private $connection;

  function __construct() {
    $this->connection = $this->connectToDb();
  }

  function connectToDb(){
    $serverName = 'localhost';
    $userName = 'root';
    $password = 'root';
    $dbName = 'rm';
    $port = '8889';

    $conn =  new mysqli($serverName, $userName, $password, $dbName, $port );
    if (mysqli_connect_errno()) {
      printf('Connection Error: %s\n', mysqli_connect_error());
      exit();
    }

    mysqli_set_charset ( $conn, 'utf8' );
    return $conn;
  }

  function getConnection(){
    return $this->connection;
  }

  function unSetConnection(){
    $this->connection->close();
  }
}