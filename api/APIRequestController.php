<?php

class APIRequestController {
  private $id;
  private $requestMethod;
  private $responseGenerator;

  /**
   * APIRequestController constructor.
   * @param $id
   * @param $requestMethod
   * @param $responseGenerator
   */
  public function __construct($id, $requestMethod, ResponseGenerator $responseGenerator) {
    $this->id = $id;
    $this->requestMethod = $requestMethod;
    $this->responseGenerator = $responseGenerator;
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->id) {
          $response = $this->get($this->id);
        } else {
          $response = $this->getAll();
        };
        break;
      case 'POST':
        $response = $this->createFromRequest();
        break;
      case 'PUT':
        $response = $this->updateFromRequest($this->id);
        break;
      case 'DELETE':
        $response = $this->delete($this->id);
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

  private function get($id) {
    $result = $this->responseGenerator->get($id);

    if (! $result) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result->jsonSerialize());
    return $response;
  }

  private function getAll() {
    $resultArray = $this->responseGenerator->getAll();
    if (! $resultArray) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    foreach ($resultArray as $item) {
      $result[] = $item->jsonSerialize();
    }
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createFromRequest() {
    $input = (array) json_decode(file_get_contents('php://input'));

    if (! $this->validate($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->responseGenerator->createNew($input);

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode([
       'success' => 'Successfully created'
    ]);
    return $response;
  }

  private function updateFromRequest($id) {
    $result = $this->responseGenerator->get($id);
    if (!$result || !$result->getId() ) {
      return $this->notFoundResponse();
    }
    $input = (array) json_decode(file_get_contents('php://input'), true);

    if (! $this->validate($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->responseGenerator->update($id, $input);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode([
       'success' => 'Successfully updated'
    ]);
    return $response;
  }

  private function validate($input) {
    return $this->responseGenerator->validate($input);
  }

  private function delete($id) {
    $result = $this->responseGenerator->get($id);
    if (! $result) {
      return $this->notFoundResponse();
    }
    if(!$this->responseGenerator->delete($id)) {
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