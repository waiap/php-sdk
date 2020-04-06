<?php
    require './vendor/autoload.php';

    $server_request = file_get_contents('php://input');
    $client = new \PWall\Client();
    $client->setEnvironment("sandbox");
    $client->setKey("<inserte su key proporcionada por su integrador>");
    $client->setResource("<insert el nombre del resource proporcionado por su integrador");
    $client->setSecret("<inserte el secret porporcionado por su integrador>");
    $client->setBackendUrl("https://domain.host/example/checkout.html");
    $client->setDebugFile("/var/www/log/debug.log"); // logger .   
    $request = new \PWall\Request($server_request, false);

    $request->setAmount(1.00);
    $request->setCurrency("EUR");
    $request->setGroupId(0);
    $request->setNotifyResult("https://domain.host/example/checkout.html");
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

    echo $response->toJSON();
