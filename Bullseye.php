<?php
namespace Bullseye;

require_once "Connection.php";
include_once "modules/Location.php";
include_once "modules/Search.php";
include_once "modules/Lead.php";
include_once "modules/User.php";
include_once "modules/EventSearch.php";

/**
 * Front controller to handle all requests make to Bullseye API. This class loads other modules
 * based on requests to the API.
 */
class Bullseye{

  /**
   * Connection to Bullseye REST API
   */
  private $connection;

  /**
   * Create Bullseye object to make requests to the REST API.
   *
   * @param $clientId integer ID of client in Bullseye.
   * @param $searchKey string Search key of client in Bullseye.
   * @param $adminKey string Admin key of client in Bullseye.
   * @param $staging boolean if true, then a connection to staging server instead production server is created.
   */
  function __construct($clientId, $searchKey, $adminKey = null, $staging = false){
    //create connection
    $this->connection = new Connection($clientId, $searchKey, $adminKey, $staging);
  }

  /**
   * Enable or disable debug messages for Bullseye connections.
   *
   * @param $activate boolean if true, then debug messages are activated. Otherwise, debug messages are disabled.
   */
  function debug($activate = true){
    $this->connection->debug = $activate;
  }
  
  /**
   * Returns the error in last request made to Bullseye.
   *
   * @return mixed if there was an error in last request made, then an array with error info is returned. Otherwise null is returned.
   */
  function getLastError(){
    return $this->connection->getLastError();
  }

  /**
   * http://api.bullseyelocations.com/services/getlocation-method-0
   *
   * Location module.
   */
  function getLocation($locationId){
    return Location::getLocation($this->connection, $locationId);
  }

  /**
   * http://api.bullseyelocations.com/services/addlocation-method
   *
   * Location module.
   */
  function addLocation($locationData){
    return Location::addLocation($this->connection, $locationData);
  }

  /**
   * http://api.bullseyelocations.com/services/updatelocation-method
   *
   * @return mixed false if there is an error. Otherwise the request response.
   *
   * Location module.
   */
  function updateLocation($locationId, $locationData){
    return Location::updateLocation($this->connection, $locationId, $locationData);
  }

  /**
   * http://api.bullseyelocations.com/services/deletelocations-method
   *
   * Location module.
   */
  function deleteLocation($locationId){
    return Location::deleteLocation($this->connection, $locationId);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getallcountries
   *
   * Location module.
   */
  function getAllCountries(){
    return Location::getAllCountries($this->connection);
  }

  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getholidayhours
   *
   * Location module.
   */
  function getHolidayHours($args){
    return Location::getHolidayHours($this->connection, $args);
  }
  
  /**
   * http://api.bullseyelocations.com/services/dosearch2-method
   *
   * Search module.
   */
  function searchLocations($args){
    return $this->connection->process_query(Search::$actions['DoSearch2'], $args);
  }
  
  /**
   * http://api.bullseyelocations.com/services/getcategories-method
   *
   * Search module.
   */
  function getCategories($order = false){
    return $this->connection->process_query(Search::$actions['GetCategories'], null, compact('order'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getcatsum
   *
   * Search module.
   */
  function getCatSum($args){
    return $this->connection->process_query(Search::$actions['GetCatSum'], $args);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getstatesbycountry
   *
   * Search module.
   */
  function getStatesByCountry($countryId){
    return $this->connection->process_query(Search::$actions['GetStatesByCountry'], compact('countryId'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getcountrylist
   *
   * Search module.
   */
  function getCountryList(){
    return $this->connection->process_query(Search::$actions['GetCountryList']);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getcountriescontainedinterritories
   *
   * Search module.
   */
  function getCountriesContainedInTerritories(){
    return $this->connection->process_query(Search::$actions['GetCountriesContainedInTerritories']);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getcitylist
   *
   * Search module.
   */
  function getCityList($CountryId, $StateAbbr = null){
    return $this->connection->process_query(Search::$actions['GetCityList'], compact('CountryId', 'StateAbbr'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getcatlist
   *
   * Search module.
   */
  function getCatList($CountryId, $State = null, $City = null){
    return $this->connection->process_query(Search::$actions['GetCatList'], compact('CountryId', 'State', 'City'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getattributeoptions
   *
   * Search module.
   */
  function getAttributeOptions($AttributeId = null){
    return $this->connection->process_query(Search::$actions['GetAttributeOptions'], compact('AttributeId'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getattributes
   *
   * Search module.
   */
  function getAttributes(){
    return $this->connection->process_query(Search::$actions['GetAttributeOptions']);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getpostalcode
   *
   * Search module.
   */
  function getPostalCode($CountryId, $PostalCode = null){
    return $this->connection->process_query(Search::$actions['GetPostalCode'], compact('CountryId', 'PostalCode'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getsearchlog
   *
   * Search module.
   */
  function getSearchLog($startDate, $endDate){
    return $this->connection->process_query(Search::$actions['GetSearchLog'], compact('startDate', 'endDate'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getcategorytree
   *
   * Search module.
   */
  function getCategoryTree($LanguageID = null, $LanguageCode = null){
    return $this->connection->process_query(Search::$actions['GetCategoryTree'], compact('LanguageID', 'LanguageCode'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getclientsearchsettings-2
   *
   * Search module.
   */
  function getClientSearchSettings($RegionId){
    return $this->connection->process_query(Search::$actions['GetClientSearchSettings'], compact('RegionId'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#addlead
   *
   * Lead module.
   */
  function addLead($args){
    return $this->connection->process_query(Lead::$actions['AddLead'], $args);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#getleadsources
   *
   * Lead module.
   */
  function getLeadSources(){
    return $this->connection->process_query(Lead::$actions['GetLeadSources']);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#testinput-1
   *
   * Lead module.
   */
  function authenticateClient(){
    return $this->connection->process_query(Lead::$actions['AuthenticateClient']);
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#changepassword
   *
   * User module.
   */
  function changePassword($userName, $newPassword){
    return $this->connection->process_query(User::$actions['ChangePassword'], compact('userName', 'newPassword'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#changeusername
   *
   * User module.
   */
  function changeUsername($oldUserName, $newUserName){
    return $this->connection->process_query(User::$actions['ChangeUsername'], compact('oldUserName', 'newUserName'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#changeactivestatus
   *
   * User module.
   */
  function changeActiveStatus($userName, $activeStatus){
    return $this->connection->process_query(User::$actions['ChangeActiveStatus'], compact('userName', 'activeStatus'));
  }
  
  /**
   * https://bullseyelocations.readme.io/v1.0/reference#eventsearch
   *
   * Event Search module.
   */
  function eventSearch($args){
    return $this->connection->process_query(EventSearch::$actions['EventSearch'], $args);
  }
}
