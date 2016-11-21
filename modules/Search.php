<?php
namespace Bullseye;

class Search{

  /**
   * http://api.bullseyelocations.com/services/dosearch2-method
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function search($connection, $args) {
    list($httpcode, $response) = $connection->query("get", 'RestSearch.svc/DoSearch2', $args);
    if($httpcode !== $connection::HTTP_OK) {
      return false;
    }
    return $response;
  }
}
