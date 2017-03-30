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

//3. Call method to search for logs
$startDate = '20170301';
$endDate = '20170331';
$response = $bullseye->getSearchLog($startDate, $endDate);

//4. Check response
if(false !== $response)
  print_r($response);
else{
  //if location was not found
  print_r($bullseye->getLastError());
}

