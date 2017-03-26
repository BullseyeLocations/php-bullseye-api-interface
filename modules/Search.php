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
  
  /**
   * http://api.bullseyelocations.com/services/getcategories-method
   *
   * @param $order boolean if true, then categories are ordered alphabetically. Default is false.
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function getCategories($connection, $order = false){
    list($httpcode, $response) = $connection->query("get", 'RestSearch.svc/GetCategories');
    if($httpcode !== $connection::HTTP_OK) {
      return false;
    }
    
    //make ordering
    if($order){
      usort($response, function($a, $b){
        return strcasecmp($a['CategoryName'], $b['CategoryName']);
      });
    }
    
    return $response;
  }
}
