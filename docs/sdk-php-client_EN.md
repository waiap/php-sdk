---
id: sdk-php-client
title: Client Class
---

The class `\PWall\Client` is responsible for making proxy requests for both the Payment Wall of the payment page and the shop administrator. It can be initialized in two different ways, through a configuration file or through parameters.

## Configuration

### Using a configuration file

This file will use the PHP directive define to define the following constants:

- `PWALL_ENV`: Determines the environment to be used to make the requests. It has two possible values: sandbox, used to make test without making real payment and live, used to make real payments in production.
- `PWALL_KEY`: The key parameter provided by Waiap.
- `PWALL_RESOURCE`: The resource parameter provided by Waiap.
- `PWALL_SECRET`: The secret parameter provided by Waiap.
- `PWALL_BACKEND_URL`: Sets the URL where the Payment Wall is being rendered, so the redirects can be made if the payment method requieres it.
- `PWALL_DEBUG_FILE`: Sets up a route to record the calls and responses received by the client. This parameter is optional.
- `PWALL_TIMEOUT`:  Sets the maximum response time when making a request. This parameter is optional.

Using this configuration file we can initialize the client in the following way:

``` php
$client = new \PWall\Client();
```

But if we prefer to use the way of initialization by parameters we must use the class' methods.

### Methods

The methods used for initializate the client are as follows:

- `setEnvironment(String $enviroment)`: Equal to the constant `PWALL_ENV`
- `setKey(String $key)`: Equal to the constant `PWALL_KEY`
- `setResource(String $resource)`: Equal to the constant `PWALL_RESOURCE`
- `setSecret(String $secret)`: Equal to the constant `PWALL_SECRET`
- `setBackendUrl(String $url)`: Equal to the constant `PWALL_BACKEND_URL`
- `setDebugFile(String $path)`: Equal to the constant `PWALL_DEBUG_FILE`
- `setTimeout(int $timeout)`: Equal to the constant `PWALL_TIMEOUT`

An example using the methods for initialization would be as follows:

``` php
$client = new \PWall\Client();
$client->setEnvironment("sandbox");
$client->setKey("key");
$client->setResource("resource");
$client->setSecret("secret");
$client->setBackendUrl("https://my-website/checkout");
$client->setDebugFile("file_path_log");
$client->setTimeout(30000);
```

## To send the request

Finally, the method in charge of making proxy calls for the various communications will be the `proxy` method. This method will return a `\PWall\Response` class which is discussed in the next section.

To make a proxy request of any type it will be sufficient to construct a request with the `\PWall\Request` class and pass it to the proxy methor of `\PWall\Client`. An example of use would be the following:

``` php
$server_request = file_get_contents('php://input');
$client = new \PWall\Client(); // Using the file for initialization.
$request = new \PWall\Request($server_request, true); // Request of type administrator.

$response = $client->proxy($request);
```