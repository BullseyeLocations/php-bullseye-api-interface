<?php
namespace Bullseye;

class Connection{
  /**
   * URL of REST services.
   * @var url
   */
  private $production_url = "https://ws.bullseyelocations.com";
  private $staging_url = "https://ws.bullseyelocations-staging.com";
  
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
   * Store client token to authentcate requests.
   */
  private $clientToken = null;

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
    
    //get Client Token
    $clientToken = $this->process_query([
      'httpMethod' => 'get',
      'action' => 'RestLead.svc/AuthenticateClient'
    ]);
    if(false !== $clientToken)
      $this->clientToken = $clientToken;
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
   * @param $clientToken boolean if true, then ClientToken HTTP header is used to authenticate request, instead ClientId and ApiKey.not used. Default false.
   */
  public function query($httpMethod, $action, $args = array(), $useClientToken = false) {
    //append ClientId and ApiKey to request
    if(!$useClientToken){
      //set Api key to use in the request
      $args['ApiKey'] = $this->adminKey ? $this->adminKey : $this->searchKey;

      // Specific ClientId for all calls
      $args['ClientId'] = $this->clientId;
    }

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
    $options[CURLOPT_HTTPHEADER] = [];

    //check if request is POST
    if("post" == $httpMethod) {
      $dataString = json_encode($args);

      $options[CURLOPT_POST] = 1;
      $options[CURLOPT_CUSTOMREQUEST] = "POST";
      $options[CURLOPT_POSTFIELDS] = $dataString;
      $options[CURLOPT_HTTPHEADER] = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($dataString)
      );
    }
    
    //add ClientToken header to authenticate request
    if($useClientToken)
      $options[CURLOPT_HTTPHEADER] []= 'ClientToken: ' . $this->clientToken;

    //execute request
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    //if debug is enabled
    if($this->debug){
      echo "Curl Info: ";
      print_r(curl_getinfo($curl));
      
      if("post" == $httpMethod) {
        echo "Post Data: ";
        print_r($args);
      }
      
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
    if($httpcode !== self::HTTP_OK)
      $this->lastError = array(
        'code' => $httpcode,
        'response' => json_decode($response, true)
      );
    else
      $this->lastError = null;
    
    //returns response
    return array($httpcode, json_decode($response, true));
  }
  
  /**
   * This function makes a query and process its response. It is created to not duplicate code in most of requests.
   *
   * @param $method array Associative array with info of method to execute. i.e:
   *   'GetCatSum' => [
   *     'httpMethod' => 'get',
   *     'action' => 'ModulePath/MyAction',
   *     'callbacks' => [ //<-- optional
   *       'before_query' => 'my_fun_1',
   *       'after_query' => [
   *         'class' => 'MyClass', //<-- optional
   *         'method' => 'my_fun_2'
   *       ]
   *     ]
   *   ]
   * @param $args array Associative array with data to send in request.
   * @param $clientToken boolean if true, then ClientToken HTTP header is used to authenticate request, instead ClientId and ApiKey. Default false.
   *
   * @return mixed false if there is an error. Otherwise the request response.
   */
  public function process_query($method, $args = array(), $callbacksArgs = array(), $useClientToken = false){
    //extract arg to make query
    $httpMethod = $action = $callbacks = null;
    extract($method);
    
    //first callback to edit args before query execution
    if(!empty($callbacks['before_query']))
      $args = self::executeCallback($callbacks['before_query'], $callbacksArgs, $args);
  
    //makes requests
    list($httpcode, $response) = $this->query($httpMethod, $action, $args, $useClientToken);
    
    //validate HTTP code of response
    if($httpcode !== self::HTTP_OK) {
      return false;
    }
    
    //second callback before returning response
    if(!empty($callbacks['after_query']))
      $response = self::executeCallback($callbacks['after_query'], $response, $callbacksArgs, $args);
    
    //returns response
    return $response;
  }
  
  /**
   * This function executes callbacks in process_query function.
   */
  private static function executeCallback($callback, $param1, $param2, $param3 = null){
    //check callback data
    if(!empty($callback['class']) && !empty($callback['method']) && method_exists($callback['class'], $callback['method'])){
      return call_user_func([$callback['class'], $callback['method']], $param1, $param2, $param3);
    }
    
    return false;
  }
}

