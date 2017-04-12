<?php
namespace Bullseye;

/**
 * Handle requests to user module in Bullseye REST API.
 * http://api.bullseyelocations.com/services/user-web-service
 */
class User{

  /**
   * Meta data to make requests of Lead module.
   */
  static $actions = array(
  
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#changepassword
     */
    'ChangePassword' => array(
      'httpMethod' => 'post',
      'action' => 'RestUser.svc/ChangePassword',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#changeusername
     */
    'ChangeUsername' => array(
      'httpMethod' => 'post',
      'action' => 'RestUser.svc/ChangeUsername',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#changeactivestatus
     */
    'ChangeActiveStatus' => array(
      'httpMethod' => 'post',
      'action' => 'RestUser.svc/ChangeActiveStatus',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#createuser
     */
    'CreateUser' => array(
      'httpMethod' => 'post',
      'action' => 'RestUser.svc/CreateUser',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getuser
     */
    'GetUser' => array(
      'httpMethod' => 'get',
      'action' => 'RestUser.svc/GetUser',
    ),
  );
}

