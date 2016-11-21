<?php
namespace Bullseye;

class Connection{
  /**
   * URL of REST services.
   * @var url
   */
  private $production_url = "http://leadmanagerws.electricvine.com";
  private $staging_url = "http://leadmanagerws.staging.electricvine.com";
  
  /**
   * Client ID in Bullseye platform.
   */
  private $clientId;

  /**
   * Use in some method service for read - write access.
   * @var adminKey
   */
  private $adminKey;

  /**
   * Use in some method service for only read access.
   * @var searchKey
   */
  private $searchKey;
  /**
   * ID of client owner of search and admin keys. It is required in requests to REST services.
   * @var clientId
   */

  /**
   * Flag to indicate if staging instance is used instead production instance.
   */
  private $staging = false;
  
  /**
   * Flag to indicate if debug is enabled.
   */
  public $debug = false;

  const HTTP_OK = 200;
  
  /**
   * Store error making the last request to Bullseye.
   */
  private $lastError = null;

  /**
   * Create a connection object for a Bullseye client.
   *
   * @param $clientId integer ID of client in Bullseye.
   * @param $searchKey string Search key of client in Bullseye.
   * @param $adminKey string Admin key of client in Bullseye.
   * @param $staging boolean if true, then a connection to staging server instead production server is created.
   */
  function __construct($clientId, $searchKey, $adminKey = null, $staging = false){
    $this->clientId = $clientId;
    $this->searchKey = $searchKey;
    $this->adminKey = $adminKey;
    $this->staging = $staging;
  }
  
  function getClientId(){
    return $this->clientId;
  }
  
  /**
   * Returns the error in last request made to Bullseye.
   */
  function getLastError(){
    return $this->lastError;
  }

  /**
   * Internal fuction to call the API
   * API urls are formed by URL / <service>.svc / <method>
   *
   * @param $httpMethod string GET or POST.
   * @param $action string method to call in Bullseye API (i.e: RestLocation.svc/AddLocation)
   * @param $args array arguments to send in the request. ClientId and AdminKey are already included.
   * @param $decodeAssoc boolen if it is true, then objects decoded are created as associative arrays.
   */
  public function query($httpMethod, $action, $args = array(), $decodeAssoc = true) {
    //set Api key to use in the request
    $args['ApiKey'] = $this->adminKey ? $this->adminKey : $this->searchKey;

    // Specific ClientId for all calls
    $args['ClientId'] = $this->clientId;

    //build URL of web service
    $fullUrl = $this->staging ? $this->staging_url : $this->production_url;
    $fullUrl .= '/' . $action;

    //check if request is GET
    $httpMethod = strtolower($httpMethod);
    if( "get" == $httpMethod) {
      $fullUrl .= '?'. http_build_query($args);
    }

    //build CURL object
    $curl = curl_init();
    $options = array();
    $options[CURLOPT_URL] = $fullUrl;
    $options[CURLOPT_RETURNTRANSFER] = true;

    //check if request is POST
    if( "post" == $httpMethod) {
      $dataString = json_encode($args);

      $options[CURLOPT_POST] = 1;
      $options[CURLOPT_CUSTOMREQUEST] = "POST";
      $options[CURLOPT_POSTFIELDS] = $dataString;
      $options[CURLOPT_HTTPHEADER] = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($dataString));
    }

    //execute request
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    //if debug is enabled
    if($this->debug){
      echo "Curl Info: ";
      print_r(curl_getinfo($curl));
      
      echo "Response: ";
      print_r($response);
    }

    //get HTTP response code for the request
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    //validate response
    if ($err) {
      //store error making the request
      $this->lastError = array(
        'code' => $httpcode,
        'response' => $err
      );
    
      return array($httpcode, $err);
    }
    
    //store error in request response
    if($httpcode !== SELF::HTTP_OK)
      $this->lastError = array(
        'code' => $httpcode,
        'response' => json_decode($response, $decodeAssoc)
      );
    else
      $this->lastError = null;
    
    //returns response
    return array($httpcode, json_decode($response, $decodeAssoc));
  }
}
