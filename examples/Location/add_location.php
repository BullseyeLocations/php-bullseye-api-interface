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

//3. Call method to add the location
$locationData = array(
  "Active" => true,
  "Address1" => "3 Executive Dr",
  "Address2" => null,
  "Address3" => null,
  "Address4" => null,
  "Attributes" => array(),
  "BusinessHours" => null,
  "Categories" => array(
    array("CategoryId" => 127),
    array("CategoryId" => 128)
  ),
  "City" => "Somerset",
  //"ClientId" => $clientId, //added automatically by library
  "ContactName" => "Carlos Test",
  "ContactPosition" => null,
  "CountryId" => 1,
  "EmailAddress" => "john.doe@example.com",
  "FacebookPageId" => "10153101584008606",
  "FaxNumber" => null,
  "Id" => null,		// must be null on insert.
  "InternetLocation" => false,
  "Latitude" => 42.151439,
  "LocationTypeId" => null,	// leave null to set to dflt (std)
  "Longitude" => -120.493879,
  "MobileNumber" => null,
  "Name" => "Rest Sample Test Location",
  "PhoneNumber" => "123-456-7890",
  "PostCode" => "08873",
  "RestLocationImage" => array(
    array("ImageFileUrl" => "http://identity.rutgers.edu/sites/identity/files/RU_Shield_th.gif"),
    array("ImageFileUrl" => "http://identity.rutgers.edu/sites/identity/files/spirit_mark_pg.gif")
  ),
  "RestLocationSEO" => array( 
    "SEOTitle" => "Rest Sample Test Location", 
    "MetaDescription" => "A Sample Location for providing a code example for adding a location to Bullseye." 
  ),
  "RestLocationSocialMedia" => array( 
    "PinterestURL" => "rutgersu",
    "TwitterURL" => "RutgersU"
  ),
  "Services" => array(
    array(
      "ServiceID" => 1,
      "ServiceName" => "Lead Manager"
    ),
    array( 
      "ServiceID" => 2,
      "ServiceName" => "Store Locator"
    )
  ),
  "StateId" => 35,
  "Territories" => array(
    array("TerritoryId" => 706)
  ),
  "ThirdPartyId" => null, //"ABC",
  "URL" => null,
  // should create a LocationAdmin user:
  "UserName" => "testuser@test123.com",
  "Password" => "password123"
);
$response = $bullseye->addLocation($locationData);

//4. Check response
if($response)
  print_r($response);
else{
  //if location was not found
  print_r($bullseye->getLastError());
}

