<?php
namespace Bullseye;

/**
 * Handle requests to Event Search module in Bullseye REST API.
 */
class EventSearch{

  /**
   * Meta data to make requests of Event Search module.
   */
  static $actions = array(
  
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#eventsearch
     */
    'EventSearch' => array(
      'httpMethod' => 'get',
      'action' => 'RestEventSearch.svc/EventSearch',
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getcategories
     */
    'GetCategories' => array(
      'httpMethod' => 'get',
      'action' => 'RestEventSearch.svc/GetCategories',
    ),
  );
}

