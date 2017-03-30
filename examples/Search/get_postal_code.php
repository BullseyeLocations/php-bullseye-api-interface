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

//3. Call method to get postal code information
$countryId = 1;
$postalCode = '10098';
$response = $bullseye->getPostalCode($countryId, $postalCode);

//4. Check response
if(false !== $response)
  print_r($response);
else{
  //if location was not found
  print_r($bullseye->getLastError());
}

