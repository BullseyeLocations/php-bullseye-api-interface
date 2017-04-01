<?php
namespace Bullseye;

class Lead{

  /**
   * Meta data to make requests of Lead module.
   */
  static $actions = array(
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#addlead
     */
    'AddLead' => array(
      'httpMethod' => 'post',
      'action' => 'RestLead.svc/AddLead',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getleadsources
     */
    'GetLeadSources' => array(
      'httpMethod' => 'get',
      'action' => 'RestLead.svc/GetLeadSources',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#testinput-1
     */
    'AuthenticateClient' => array(
      'httpMethod' => 'get',
      'action' => 'RestLead.svc/AuthenticateClient',
    ),
  );
}

