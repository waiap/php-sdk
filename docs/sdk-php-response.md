---
id: sdk-php-response
title: Clase Response
---

La clase `\PWall\Response` contiene la respuesta que deberá ser devuelta al Payment Wall para su procesado. No es necesario la inicialización de la misma, ya que el método `proxy` de `\PWall\Client` se encarga de ello. 

Si la respuesta es para la realización de una autorización de venta la clase será `\PWall\SaleResponse`, la cual extiende la clase `\PWall\Response`. En ambas clases los métodos son los mismos.

## Métodos

Tanto para la clase `\PWall\Response` como para la clase `\PWall\SaleResponse` los métodos disponibles son los siguientes:

- `toJSON()`: Devuelve la respuesta JSON en un tipo string para devolverlo como respuesta de la petición del Payment Wall. 
- `getErrorCode()`: Devuelve el código de error, en caso de que haya un error.
- `getErrorMessage()`: Devuelve el mensaje de error, en caso de que haya un error.
- `isValid()`: Comprueba que la respuesta es válida para ser procesada por el Payment Wall.
- `canPlaceOrder()`: Comprueba si la respuesta es válida para realizar el proceso de pedido.
- `getPaidAmount()`: Devuelve la cantidad cobrada, en caso de ser una respuesta de venta.
- `getPaymentMethod()`: Devuelve el método de pago, en caso de ser una respuesta de venta.
- `getPaymentInfo()`: Devuelve la información de pago en un array flatenizado, en caso de ser una respuesta de venta.

Un ejemplo de utilización de la clase en el caso de una petición sin venta sería el siguiente:
``` php
$server_request = file_get_contents('php://input');
$client = new \PWall\Client(); // Utilizando la inicialización por archivo de configuración
$request = new \PWall\Request($server_request, true); // Petición de tipo administrador

$response = $client->proxy($request);

if(!$response->isValid()){
    // Utilizado para mostrar un error
    $response->getErrorCode();
    $response->getErrorMessage();
}
return $response->toJSON();
```

Por otro lado un ejemplo de venta utilizando la clase sería el siguiente:
``` php
$server_request = file_get_contents('php://input');
$client = new \PWall\Client(); // Utilizando la inicialización por archivo de configuración

$request = new \PWall\Request($server_request, false);

$request->setAmount(1.00);
$request->setCurrency("EUR");
$request->setGroupId(0);
$request->setOrderId("1");

$response = $client->proxy($request);

if($response->canPlaceOrder()){
    // Aquí se incluiría el código para cerrar el pedido.
    // Se puede utilizar la siguiente información adicional.
    $response->getPaidAmount();
    $response->getPaymentMethod(); 
    $response->getPaymentInfo();   
 }else{
    if(!$response->isValid()){
      // Utilizado para mostrar un error
      $response->getErrorCode();
      $response->getErrorMessage();
    }
}
return $response->toJSON();
```