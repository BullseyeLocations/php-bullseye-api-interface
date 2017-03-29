<?php
namespace Bullseye;

/**
 * Handle requests to user module in Bullseye REST API.
 * http://api.bullseyelocations.com/services/location-web-service
 */
class Location{
  
  /**
   * http://api.bullseyelocations.com/services/getlocation-method-0
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function getLocation($connection, $locationId){
    //make request to Bullseye
    list($httpcode, $response) = $connection->query("get", 'RestLocation.svc/GetLocation', array('LocationId' => $locationId));
    //check if response is invalid
    if($httpcode !== $connection::HTTP_OK)
      return false;
    //returns response
    return $response;
  }
  
  /**
   * http://api.bullseyelocations.com/services/addlocation-method
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function addLocation($connection, $locationData){
    //unset ID from location data
    $locationData['Id'] = null;
  
    //make request to Bullseye
    list($httpcode, $response) = $connection->query("post", 'RestLocation.svc/AddLocation', array('myLoc' => $locationData));
    //check if response is invalid
    if($httpcode !== $connection::HTTP_OK)
      return false;
    //returns response
    return $response;
  }
  
  /**
   * http://api.bullseyelocations.com/services/updatelocation-method
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function updateLocation($connection, $locationId, $locationData){
    //set location ID
    $locationData['Id'] = $locationId;

    //make request to Bullseye
    list($httpcode, $response) = $connection->query("post", 'RestLocation.svc/UpdateLocation', array('myLoc' => $locationData));
    //check if response is invalid
    if($httpcode !== $connection::HTTP_OK)
      return false;
    //returns response
    return $response;
  }
  
  /**
   * http://api.bullseyelocations.com/services/deletelocations-method
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function deleteLocation($connection, $locationId){
    //make request to Bullseye
    list($httpcode, $response) = $connection->query("post", 'RestLocation.svc/DeleteLocations', array('ids' => array($locationId)));
    //check if response is invalid
    if($httpcode !== $connection::HTTP_OK)
      return false;
    //returns response
    return $response;
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getallcountries
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  static function getAllCountries($connection){
    //make request to Bullseye
    list($httpcode, $response) = $connection->query("get", 'RestLocation.svc/GetAllCountries');
    //check if response is invalid
    if($httpcode !== $connection::HTTP_OK)
      return false;
    //returns response
    return $response;
  }
}

