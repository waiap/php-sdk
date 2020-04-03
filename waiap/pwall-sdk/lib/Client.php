<?php

declare(strict_types=1);

namespace PWall;

class Client
{

  private $enviroment   = null;
  private $key          = null;
  private $resource     = null;
  private $secret       = null;
  private $backend_url  = null;
  private $debug_file   = "false";
  private $timeout      = 30000;

  public function __construct()
  {
    //REQUIERED PARAMERTS
    if(defined("PWALL_ENV") 
      && defined("PWALL_KEY") 
      && defined("PWALL_RESOURCE")
      && defined("PWALL_SECRET")
      && defined("PWALL_BACKEND_URL")
      ){
        $this->enviroment   = \PWALL_ENV;
        $this->key          = \PWALL_KEY;
        $this->resource     = \PWALL_RESOURCE;
        $this->secret       = \PWALL_SECRET;
        $this->backend_url  = \PWALL_BACKEND_URL;
    }
    //OPTIONAL PARAMETERS 
    if(defined("PWALL_DEBUG_FILE")){
      $this->debug_file   = \PWALL_DEBUG_FILE;
    }
    if(defined("PWALL_TIMEOUT")){
      $this->timeout      = \PWALL_TIMEOUT;
    }
  }

  /**
   * Proxy a request to Sipay for process
   *
   * @param  \PWall\Request $request 
   * @return \PWall\Response|\PWall\SaleResponse
   */
  public function proxy(\PWall\Request $request){
    $proxy_helper = new \PWall\Helper\ProxyHelper(
      $this->enviroment,
      $this->key,
      $this->resource,
      $this->secret,
      $this->backend_url,
      $this->debug_file,
      $this->timeout
    );

    $request->setNotifyResult($this->backend_url);

    $response = $proxy_helper->proxyRequest($request);

    if($request->isActionSale()){
      return new \PWall\SaleResponse($response);
    }else{
      return new \PWall\Response($response);
    }
  }
    
  /**
   * Sets the enviroment where the payment method will operate ('sandbox' or 'live')
   *
   * @param  String $enviroment Specific values can be 'sandbox' or 'live'
   * @return void
   */
  public function setEnvironment(String $enviroment){
    if(!array_key_exists($enviroment, \PWall\Helper\Constants::ENVIROMENTS_URLS)){
      throw new \PWall\Exception\InvalidArgumentException('Enviroment must be one of these values: ' . implode(', ', array_keys(\PWall\Helper\Constants::ENVIROMENTS_URLS)));
    }
    $this->enviroment = $enviroment;
  }

  /**
   * Sets the key that will be used for authentication with Sipay
   *
   * @param  String $key Key provided by Sipay
   * @return void
   */
  public function setKey(String $key){
    $this->key = $key;
  }
  
  /**
   * Sets the resource that will be used for authentication with Sipay
   *
   * @param  String $resource Resource provided by Sipay
   * @return void
   */
  public function setResource(String $resource){
    $this->resource = $resource;
  }
  
  /**
   * Sets the secret that will be used for authentication with Sipay
   *
   * @param  String $secret Secret provided by Sipay
   * @return void
   */
  public function setSecret(String $secret){
    $this->secret = $secret;
  }
  
  /**
   * Sets the url where the Waiap Payment Wall will be rendered on to receive 
   * notifications about payments
   *
   * @param  String $backend_url Url where Waiap Payment Wall will be rendered on
   * @return void
   */
  public function setBackendUrl(String $backend_url){
    $this->backend_url = $backend_url;
  }
  
  /**
   * Sets the file where logs will be stored for debugging purposes
   * If no debug file path is provided there will be no logs
   *
   * @param  String $debug_file Path to debug file
   * @return void
   */
  public function setDebugFile(String $debug_file = "false"){
    $this->debug_file = $debug_file;
  }
  
  /**
   * Sets timeout for requests to resolve, default is 30s
   *
   * @param  int $timeout Tiemout in ms
   * @return void
   */
  public function setTimeout(int $timeout = 30000){
    $this->timeout = $timeout;
  }

}