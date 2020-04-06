---
id: sdk-php-response
title: Response Class
---

The `\PWall\Response` class contains the response that should be returned to the Payment Wall for processing. It is not necessary to initialize it, since the `proxy` method of `\PWall\Client` takes care of it.

If the answer is for the execution of a sale authorization the class will be `\PWall\SaleResponse`, which extends the `\PWall\Response` class. In both classes the methods are the same.

## Methods

For both `\PWall\Response` class and `\PWall\SaleResponse` class, the available methods are the following:

- `toJSON()`: Returns the JSON response in a string type to return it as a response to the Payment Wall request. 
- `getErrorCode()`: Returns the error code in case there is an error.
- `getErrorMessage()`: Returns the error message in case there is an error.
- `isValid()`: Check if the answer is valid to be processed by the Payment Wall.
- `canPlaceOrder()`: Check if the answer is valid to make the order process.
- `getPaidAmount()`: Returns the amount charged in case it is a sale response.
- `getPaymentMethod()`: Returns the payment method in case it is a sale response.
- `getPaymentInfo()`: Returns the payment information in a flattened array in case it is a sale response.

An example of using the class in the case of a non-sales request would be as follows:
``` php
$server_request = file_get_contents('php://input');
$client = new \PWall\Client(); // Using the file for initialization.
$request = new \PWall\Request($server_request, true); // Request of type administrator.

$response = $client->proxy($request);

if(!$response->isValid()){
    // Used to display an error
    $response->getErrorCode();
    $response->getErrorMessage();
}
return $response->toJSON();
```

On the other hand an exampe of selling using the class would be as following:
``` php
$server_request = file_get_contents('php://input');
$client = new \PWall\Client(); // Using the file for initialization.

$request = new \PWall\Request($server_request, false);

$request->setAmount(1.00);
$request->setCurrency("EUR");
$request->setGroupId(0);
$request->setOrderId("1");

$response = $client->proxy($request);

if($response->canPlaceOrder()){
    // Here would be included the code to close the order.
    // You can use the following additional information.
    $response->getPaidAmount();
    $response->getPaymentMethod(); 
    $response->getPaymentInfo();   
 }else{
    if(!$response->isValid()){
      // Used to dsiplay an error.
      $response->getErrorCode();
      $response->getErrorMessage();
    }
}
return $response->toJSON();
```