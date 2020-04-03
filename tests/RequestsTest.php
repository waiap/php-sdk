<?php

use PHPUnit\Framework\TestCase;

require_once './vendor/autoload.php';
require_once __DIR__.'/RequestsResponses.php';

final class RequestsTest extends TestCase
{
  use \phpmock\phpunit\PHPMock;

  protected $client;

  protected function initClient(){
    $this->client = new \PWall\Client();
    $this->client->setEnvironment("sandbox");
    $this->client->setKey("sipay-test-team");
    $this->client->setResource("sipay-test-pwall");
    $this->client->setSecret("api-secret");
    $this->client->setBackendUrl("http://develop.sipay.es:3001/sipay-sdk/example");
  }

  public function testListMethods()
  {
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::LIST_METHODS_OK["response"]);

    $request = new \PWall\Request(RequestsResponses::LIST_METHODS_OK["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertFalse($response->canPlaceOrder());
  }

  public function testListMethodsKO(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::LIST_METHODS_KO["response"]);

    $request = new \PWall\Request(RequestsResponses::LIST_METHODS_KO["request"], true);

    $response = $this->client->proxy($request);

    $this->assertFalse($response->isValid());
    $this->assertFalse($response->canPlaceOrder());
  }

  public function testGetConfiguration(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::GET_CONFIGURATION_OK["response"]);

    $request = new \PWall\Request(RequestsResponses::GET_CONFIGURATION_OK["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertFalse($response->canPlaceOrder());
  }

  public function testGetConfigurationKO(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::GET_CONFIGURATION_KO["response"]);

    $request = new \PWall\Request(RequestsResponses::GET_CONFIGURATION_KO["request"], true);

    $response = $this->client->proxy($request);

    $this->assertFalse($response->isValid());
    $this->assertFalse($response->canPlaceOrder());
  }

  public function testSaleCC(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::SALE_CC_OK["response"]);

    $request = new \PWall\Request(RequestsResponses::SALE_CC_OK["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->canPlaceOrder());
  }

  public function testSaleCCKO(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::SALE_CC_KO["response"]);

    $request = new \PWall\Request(RequestsResponses::SALE_CC_KO["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertEquals("-1", $response->getErrorCode());
    $this->assertFalse($response->canPlaceOrder());
  }

  public function testSalePaypal(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::SALE_PAYPAL_OK["response"]);

    $request = new \PWall\Request(RequestsResponses::SALE_PAYPAL_KO["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->canPlaceOrder());
  }

  public function testSalePaypalKO(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::SALE_PAYPAL_KO["response"]);

    $request = new \PWall\Request(RequestsResponses::SALE_PAYPAL_KO["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertEquals("-1", $response->getErrorCode());
    $this->assertFalse($response->canPlaceOrder());
  }

  public function testSaleAmazon(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::SALE_AMAZON_OK["response"]);

    $request = new \PWall\Request(RequestsResponses::SALE_AMAZON_OK["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->canPlaceOrder());
  }

  public function testSaleAmazonKO(){
    $this->initClient();

    $curl_exec = $this->getFunctionMock('PWall\Helper', "curl_exec");

    $curl_exec->expects($this->once())->willReturn(RequestsResponses::SALE_AMAZON_KO["response"]);

    $request = new \PWall\Request(RequestsResponses::SALE_AMAZON_KO["request"], true);

    $response = $this->client->proxy($request);

    $this->assertTrue($response->isValid());
    $this->assertEquals("-1", $response->getErrorCode());
    $this->assertFalse($response->canPlaceOrder());
  }

}