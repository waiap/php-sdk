<?php

use PHPUnit\Framework\TestCase;

require_once './vendor/autoload.php';

final class ArgumentsTest extends TestCase
{

  protected $client;


  public function testPWallClient(){
    $client =  new \PWall\Client();
    $this->assertInstanceOf(\Pwall\Client::class, $client);
    $this->expectException(\PWall\Exception\InvalidArgumentException::class);
    $client->setEnvironment('environment'); 
  }

  public function testPWallRequest(){

    $request = new \PWall\Request('json_request', false);
    $this->assertInstanceOf(\Pwall\Request::class, $request);

    $request->setOrderId("123456789012");
    $request->setAmount(1.00);

    $this->assertFalse($request->isActionSale());

    $this->expectException(\PWall\Exception\InvalidArgumentException::class);
    $request->setOrderId("1234567890123");
    $request->setAmount(0);

  } 

}
