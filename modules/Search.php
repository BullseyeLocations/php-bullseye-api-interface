<?php
namespace Bullseye;

class Search{

  /**
   * Meta data to make requests of Search module.
   */
  static $actions = array(
  
    /**
     * http://api.bullseyelocations.com/services/dosearch2-method
     */
    'DoSearch2' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/DoSearch2',
    ),
    
    /**
     * http://api.bullseyelocations.com/services/getcategories-method
     */
    'GetCategories' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetCategories',
      'callbacks' => array(
        'after_query' => array(
          'class' => '\Bullseye\Search',
          'method' => 'sort_categories'
        )
      )
    ),

    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getcatsum
     */
    'GetCatSum' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetCatSum'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getstatesbycountry
     */
    'GetStatesByCountry' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetStatesByCountry'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getcountrylist
     */
    'GetCountryList' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetCountryList'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getcountriescontainedinterritories
     */
    'GetCountriesContainedInTerritories' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetCountriesContainedInTerritories'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getcitylist
     */
    'GetCityList' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetCityList'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getcatlist
     */
    'GetCatList' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetCatList'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getattributeoptions
     */
    'GetAttributeOptions' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetAttributeOptions'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getattributes
     */
    'GetAttributes' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetAttributes'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getpostalcode
     */
    'GetPostalCode' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetPostalCode'
    ),
    
    /**
     * https://bullseyelocations.readme.io/v1.0/reference#getsearchlog
     */
    'GetSearchLog' => array(
      'httpMethod' => 'get',
      'action' => 'RestSearch.svc/GetSearchLog'
    ),
  );
  
  /**
   * Used in callback after_query.
   *
   * Sort categorires alphabetically in action GetCategories.
   */
  static function sort_categories($response, $callbackArgs, $args){
    //make ordering
    if(!empty($callbackArgs['order'])){
      usort($response, function($a, $b){
        return strcasecmp($a->CategoryName, $b->CategoryName);
      });
    }
    
    return $response;
  }
}

