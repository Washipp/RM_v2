<?php

/**
 * Class APIRequestController
 *
 * This class handles a single API Request with the given constructor inputs.
 */
class APIRequestController {
  private $id;
  private $requestMethod;
  private $responseGenerator;

  /**
   * APIRequestController constructor.
   * @param $id int is optional, can be null
   * @param $requestMethod string either PUT, GET, DELETE, POST
   * @param $responseGenerator ResponseGenerator Interface to the Database class
   */
  public function __construct($id, string $requestMethod, ResponseGenerator $responseGenerator) {
    $this->id = $id;
    $this->requestMethod = $requestMethod;
    $this->responseGenerator = $responseGenerator;
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->id) { //evaluates to true if it is set
          $response = $this->getById();
        } else {

          $response = $this->getAll();
        };
        break;
      case 'POST':
        $response = $this->createNewEntity();
        break;
      case 'PUT':
        $response = $this->updateById();
        break;
      case 'DELETE':
        $response = $this->deleteById();
        break;
      case 'OPTIONS': // we have to handle OPTIONS request and return 200 for some browsers to work
        //TODO: Handle OPTIONS Request correctly
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  private function getById() {
    $result = $this->responseGenerator->get($this->id);

    if (!$result) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result->jsonSerialize());
    return $response;
  }

  private function getAll() {
    $resultArray = $this->responseGenerator->getAll();
    if (!$resultArray) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    foreach ($resultArray as $item) {
      $result[] = $item->jsonSerialize();
    }
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createNewEntity() {
    $input = (array) json_decode(file_get_contents('php://input'));

    if (!$this->validate($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->responseGenerator->createNew($input);

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode([
       'success' => 'Successfully created'
    ]);
    return $response;
  }

  private function updateById() {
    $result = $this->responseGenerator->get($this->id);
    if (!$result || !$result->getId() ) {
      return $this->notFoundResponse();
    }
    $input = (array) json_decode(file_get_contents('php://input'), true);

    if (! $this->validate($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->responseGenerator->update($this->id, $input);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode([
       'success' => 'Successfully updated'
    ]);
    return $response;
  }

  private function validate($input) {
    return $this->responseGenerator->validate($input);
  }

  private function deleteById() {
    $result = $this->responseGenerator->get($this->id);
    if (! $result) {
      return $this->notFoundResponse();
    }
    if(!$this->responseGenerator->delete($this->id)) {
      return $this->databaseErrorResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode([
       'success' => 'Successfully deleted'
    ]);
    return $response;
  }

  private function unprocessableEntityResponse() {
    $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
    $response['body'] = json_encode([
       'error' => 'Invalid input'
    ]);
    return $response;
  }

  private function databaseErrorResponse() {
    $response['status_code_header'] = 'HTTP/1.1 501 Not Implemented';
    $response['body'] = json_encode([
       'error' => 'Database Error'
    ]);
    return $response;
  }

  private function notFoundResponse() {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = json_encode([
       'error' => 'Entity not found'
    ]);
    return $response;
  }

}