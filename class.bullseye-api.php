<?php

/**
 * Connection with BullseyeApi
 * each method service is a method in this class
 */
class BullseyeApi {

  /**
   * staging URL API
   * Replace this variable with production URL in the future
   * @var url
   */
  private $url;

  /**
   * Use in some method service for read - write access
   * @var adminKey
   */
  private $adminKey;
  /**
   * Use in some method service for only read access
   * @var searchKey
   */
  private $searchKey;

  const HTTP_OK = 200;
  const HTTP_GET = 1;
  const HTTP_POST = 2;

  /**
    * Singleton method, use this method instead constructor
    * constructor method is private
    */
  public static function getApi()
  {
    static $inst = null;
    if ($inst === null) {
      $inst = new BullseyeApi();
    }
    return $inst;
  }

  /**
   * Private ctor so nobody else can instance it
   */
  private function __construct() {
    $options = get_option('clp_settings');
    if(isset($options['enviroment']) && $options['enviroment'] == 'Production') {
      $this->clientId = '4149';
      $this->adminKey =  '090671fd-a916-42dc-8916-d04168f92478';
      $this->searchKey = '268c43cf-12aa-464d-9f08-d488165bf1b7';
      $this->url = 'http://leadmanagerws.electricvine.com';
    }
    if(isset($options['enviroment']) && $options['enviroment'] == 'Staging') {
      $this->clientId = '5781';
      $this->adminKey =  '363ce8be-6aa2-4e2f-b11d-47c1014f31e2';
      $this->searchKey = 'ae7fb670-3809-4fa6-a163-88a6239a0445';
      $this->url = 'http://leadmanagerws.staging.electricvine.com';
    }
  }

  /**
    * http://api.bullseyelocations.com/services/deletelocations-method
    */
  public function deleteLocations($locationsId) {
    if(!is_array($locationsId)) {
      $locationsId = array($locationsId);
    }
    $args['ids'] = $locationsId;
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_POST, 'RestLocation.svc', 'DeleteLocations', $args);

    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
   * http://api.bullseyelocations.com/services/dosearch2-method
   */
  public function doSearch2($args) {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestSearch.svc', 'DoSearch2', $args);
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/services/getattributes
    */
  public function getAttributes() {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestSearch.svc', 'GetAttributes');
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/services/getattributeoptions
    */
  public function getAttributeOptions($attributeId) {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestSearch.svc', 'GetAttributeOptions', array('attributeId' => $attributeId));
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/services/getcategories-method
    */
  public function getCategories() {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestSearch.svc', 'GetCategories');
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/services/getstatesbycountry-method
    */
  public function getStatesByCountry() {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestSearch.svc', 'GetStatesByCountry', array('CountryId' => 1));
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/services/addlocation-method
    */
  public function addLocation($locationArray) {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_POST, 'RestLocation.svc', 'AddLocation', array('myLoc' => $locationArray));

    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/services/addlocation-method
    */
  public function getLocation($locationId) {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestLocation.svc', 'GetLocation', array('LocationId' => $locationId));
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
   *

   */
  public function addLead($lead, $emailLead = false, $emailDealers = false, $doNotRoute = false, $locationId = false) {
    $args = array(
      'NewLead'      => $lead,
      'EmailLead'    => $emailLead,
      'EmailDealers' => $emailDealers,
      'DoNotRoute'   => $doNotRoute
    );
    if ($locationId) {
      $args['LocationId'] = $locationId;
    }
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_POST, 'RestLead.svc', 'AddLead', $args);
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
   * Internal fuction to call the API
   * API urls are formed by URL / <service>.svc / <method>
   *
   * @param method int HTTP method GET or POST, use self::HTTP_GET or self::HTTP_POST
   * @param service String the service name without include .svc , eg 'RestSearch.svc'
   * @param actions String the method service name , eg 'GetAttributes'
   * @param $args Array method service arguments
   *              ApiKey and ClientId arguments are default
   */
  private function query($method, $service, $action, $args = array()) {

    if( !isset($args['ApiKey']) ) {
      $args['ApiKey'] = $this->adminKey;
    }

    // Specific ClientId for all calls
    $args['ClientId'] = $this->clientId;

    $fullUrl = $this->url . '/' . $service . '/' . $action ;
    if( $method === self::HTTP_GET) {
      $fullUrl .= '?'. http_build_query($args);
    }

    $curl = curl_init();
    $options = array();
    $options[CURLOPT_URL] = $fullUrl;
    $options[CURLOPT_RETURNTRANSFER] = true;

    if( $method === self::HTTP_POST) {
      $dataString = json_encode($args);

      $options[CURLOPT_POST] = 1;
      $options[CURLOPT_CUSTOMREQUEST] = "POST";
      $options[CURLOPT_POSTFIELDS] = $dataString;
      $options[CURLOPT_HTTPHEADER] = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($dataString));

    }

    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $err = curl_error($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($err) {
      return array($httpcode, $err);
    }
    return array($httpcode, $response);
  }

  /**
    * http://api.bullseyelocations.com/services/updatelocation-method
    */
  public function updateLocation($locationArray) {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_POST, 'RestLocation.svc', 'UpdateLocation', array('myLoc' => $locationArray));
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }

  /**
    * http://api.bullseyelocations.com/GetAllClientSearchSettings
    */
  public function getAllClientSearchSettings() {
    list($httpcode, $jsonResponse) = $this->query(self::HTTP_GET, 'RestSearch.svc', 'GetAllClientSearchSettings', array('ApiKey' => $this->searchKey));
    if($httpcode !== self::HTTP_OK) {
      return $jsonResponse;
    }
    $objResponse = json_decode($jsonResponse, true);
    return $objResponse;
  }
}
