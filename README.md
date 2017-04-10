# Bullseye

[Bullseye](http://www.bullseyelocations.com/) is an elegant and flexible store locator solution that integrates with any website.

Bullseye has a unique focus on the right implementation for the right client. We were one of the first to offer a hosted service and we were one of the first to create a web service API that allows full control over the look and feel of the interface. Though our turnkey interfaces offer an easy to set up approach, the API gives you the ability to really maximize the value of your store/dealer locator. Do it yourself or tap our roster of seasoned developers with location-based expertise.

# PHP Library

The Bullseye PHP library is an effort to make easier using the Bullseye REST API through PHP. The library is in charge of handling and processing requests to the REST API and also provides an interface to request the API easily just creating a Bullseye object and calling its methods.

## Installation

Installation is very easy, it just requires to clone or copy the library folder in your folder and then creante an instance of Bullseye class in next way:
```
require_once '../vendor/Bullseye.php';
$clientId = 1111;
$searchKey = 'my-search-key';
$adminKey = 'my-admin-key';
$bullseye = new Bullseye\Bullseye($clientId, $searchKey, $adminKey);
```

## Example

There are examples for all REST API endpoints in [Bullseye documentation](https://bullseyelocations.readme.io/docs/getting-started).
Next is an example about how to make a request using the library:

```
//1. Include Bullseye library
require_once '../../Bullseye.php';
//2. Create Bullseye object
$clientId = 1111;
$searchKey = 'my-search-key';
$adminKey = 'my-admin-key';
$bullseye = new Bullseye\Bullseye($clientId, $searchKey, $adminKey);
//2.1 activate debug mode
//$bullseye->debug(true);
//3. Call method to get categories
$response = $bullseye->getCategories();
//4. Check response
if(false !== $response)
  //...do your stuff...
else
  print_r($bullseye->getLastError());
```

## Methods in Bullseye class

Most of methods in Bullseye class uses same names of REST API endpoints in lower camelcase. However we have used custom names for some endpoints just for clarity and also to avoid conflicts with other method names. Next is the list of methods with custom names.

  - RestSearch.svc/doSearch2 -> searchLocations
  - RestEventSearch.svc/GetCategories -> getEventCategories

# Author

The Bullseye PHP library was created and is maintained by [Bullseye team](http://www.bullseyelocations.com/company/about).

