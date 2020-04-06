---
id: sdk-php-request
title: Request Class
---

The class `\PWall\Request` is in charge of encapsulating the call made by the Payment Wall in the frontend application so that it can be sent to the proxy call that will be make by the class `\PWall\Client`.


This class receives two parameters in the constructor:

- request: request made by the Payment Wall in `string ` format.
- isBackOffice: `boolean` to make the request in backoffice mode. If the request we are receiving is used to display the Payment Wall in the store manager we can set this parameter to true so that the other methods don't have to be called. By default this parameter is set to `false ` for sales requests.

For example, to make a request that will be used in the store manager, simply use the call as follows:
```php
$frontend_request = file_get_contents('php://input'); // Get in accordance with the standards of the framework used.
$request = new \PWall\Request($frontend_request, true);
```

To use the class for a sales request, usually made on the payment page of the store, you must use the methods availables in the class.

## Methods

The call to these methods is **mandatory** if we are on the payment page of the shop.

- `setOrderId(String $order_id)`: Used to set the order Id wiwth a maximum of 12 characters. Must be unique for each purchase order.
- `setAmount(float $amount)`: Used to establish the amount to be paid for the order.
- `setCurrency(String $currency)`: Used to set the transaction currency. Must follow the standard **ISO 4217**.
- `setGroupId(int $customer_id)`: Used to establish the customer's identifier. For unregistered customers you must pass 0.

An example of using these methods for a payment would be as follows:
```php
$request = new \PWall\Request($server_request, false);
$request->setOrderId("1");
$request->setAmount(1.00);
$request->setCurrency("EUR");
$request->setGroupId(0);
```