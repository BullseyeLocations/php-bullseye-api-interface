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

//3. Call method to get the hours
$locationId = '4885827';
$location = $bullseye->getHolidayHours([
  'LocationId' => $locationId,
  //'ThirdPartyId' => $locationId,
  'FromDate' => '2017-03-20',
  //'ToDate' => $locationId,
]);

//4. Check location
if(false !== $location)
  print_r($location);
else{
  //if location was not found
  print_r($bullseye->getLastError());
}

