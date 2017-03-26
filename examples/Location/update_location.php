<?php
//1. Include Bullseye library
require_once '../../Bullseye.php';

//2. Create Bullseye object
$clientId = 5786;
$searchKey = null; //'cf623cdf-1090-43ba-8b0f-9fa67fcf9d57';
$adminKey = 'aa4c7e55-9d0b-4a97-b62e-c95efec4285e';
$useStagingServer = true;
$bullseye = new Bullseye\Bullseye($clientId, $searchKey, $adminKey, $useStagingServer);

//2.1 activate debug mode
//$bullseye->debug(true);

//3. Get location data
$locationId = '4952137';
$location = $bullseye->getLocation($locationId);

//4. Check location
if($location){
  //5. Call method to update the location
  $newLocationData = array(
    "Address3" => "This is just a test",
    "RestLocationImage" => array(
      array("ImageFileUrl" => "http://identity.rutgers.edu/sites/identity/files/RU_Shield_th.gif"),
      //array("ImageFileUrl" => "http://identity.rutgers.edu/sites/identity/files/spirit_mark_pg.gif")
    ),
    "RestLocationSocialMedia" => array( 
      "TwitterURL" => "carlostest"
    ),
  );
  $response = $bullseye->updateLocation($locationId, array_merge($location, $newLocationData));

  //6. Check response
  if($response)
    print_r($response);
  else{
    //if response is invalid
    print_r($bullseye->getLastError());
  }
}

