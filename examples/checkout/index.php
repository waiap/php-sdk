<?php
    require './vendor/autoload.php';

    $server_request = file_get_contents('php://input');
    $client = new \PWall\Client();
    $client->setEnvironment("sandbox");
    $client->setKey("<insert your key provided by your integrator>");
    $client->setResource("<insert your resource provided by your integrator");
    $client->setSecret("<insert your secret provided by your integrator>");
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
        // Here would be included the code for closing the order.
        // You can use the following additional information.
        $response->getPaidAmount();
        $response->getPaymentMethod(); 
        $response->getPaymentInfo();   
     }else{
        if(!$response->isValid()){
          // Used to display an error
          $response->getErrorCode();
          $response->getErrorMessage();
        }
    }

    echo $response->toJSON();
