<?php

declare(strict_types=1);

namespace PWall;

class Request
{
  private $request;
  private $order_id     = null;
  private $currency     = null;
  private $group_id     = null;
  private $original_url = null;
  private $notify       = null;

  public function __construct(
    $jsonRequest,
    $isAdmin
  ){
    $this->request      = json_decode($jsonRequest, true);
    $this->order_id     = $isAdmin ? str_pad("0", 12, "0", STR_PAD_LEFT) : null;
    $this->currency     = $isAdmin ? "" : null;
    $this->amount       = $isAdmin ? "0" : null;
    $this->group_id     = $isAdmin ? 0 : null;
    $this->original_url = $isAdmin ? "" : null;
  }
  
  public static function buildPaypalCartInfo($currencyCode,$items,$total){
    $cart_items = [];
    $all_digital_products = true;
    // prepare totals
    $totals                             = new \stdClass();
    $totals->total                      = new \stdClass();
    $totals->shipping                   = new \stdClass();
    $totals->tax_total                  = new \stdClass();
    $totals->discount                   = new \stdClass();
    $totals->item_total                 = new \stdClass();

    // set currency
    $totals->total->currency_code       = $currencyCode;
    $totals->shipping->currency_code    = $currencyCode;
    $totals->tax_total->currency_code   = $currencyCode;
    $totals->discount->currency_code    = $currencyCode;
    $totals->item_total->currency_code  = $currencyCode;

    // initialize amount
    $totals->total->value               = 0.0;
    $totals->shipping->value            = 0.0;
    $totals->tax_total->value           = 0.0;
    $totals->discount->value            = 0.0;

    foreach($items as $item){
      // calc per unit
      $price_per_unit                          = round((float)$item["unit_price"],2,PHP_ROUND_HALF_UP);
      $tax_per_unit                            = round((float)$item["unit_tax"],2,PHP_ROUND_HALF_UP);

      // initialize
      $paypal_item                             = new \stdClass();
      $unit_amount                             = new \stdClass();
      $paypal_item->unit_amount                = new \stdClass();
      $paypal_item->name                       = $item["name"];
      $paypal_item->quantity                   = strval($item["qty"]);
      $paypal_item->sku                        = $item["sku"];
      $paypal_item->unit_amount->currency_code = $currencyCode;
      $paypal_item->unit_amount->value         = strval($price_per_unit);
      
      if($item["is_digital"]){
        $paypal_item->category    = "DIGITAL_GOODS";
      }else{
        $paypal_item->category    = "PHYSICAL_GOODS";
        $all_digital_products     = false;
      }
      // update totals:
      $totals->total->value      += $paypal_item->unit_amount->value * $item["qty"];
      $cart_items[] = $paypal_item;
    }

    $grand_total                  =  strval(round((float)$total["total"],2,PHP_ROUND_HALF_UP));
    $total_wo_shipping            =  strval(round((float)$total["total"] - $total["shipping"],2,PHP_ROUND_HALF_UP));
    $totals->shipping->value      =  strval(round((float)$total["shipping"],2,PHP_ROUND_HALF_UP));
    $totals->tax_total->value     =  round((float)$total["tax"],2,PHP_ROUND_HALF_UP);
    $totals->discount->value      =  strval($totals->total->value + $totals->tax_total->value - $total_wo_shipping);
    $totals->total->value         =  strval($totals->total->value);
    $totals->tax_total->value     =  strval($totals->tax_total->value);
    $totals->item_total->value    =  strval($totals->total->value);

    return ["items" => $cart_items, "is_digital" => $all_digital_products, "total" => $grand_total, "breakdown"=>$totals ];
  }

  /**
   * Returns the required JSON to proxy a request to Waiap
   *
   * @return  JSON request ready to proxy to Waiap
   */
  public function toJSON(){
    $json_request                               = $this->request;
    $json_request["params"]["order"]            = $this->order_id;
    $json_request["params"]["amount"]           = $this->amount;
    $json_request["params"]["currency"]         = $this->currency;
    $json_request["params"]["group_id"]         = $this->group_id;
    $json_request["params"]["original_url"]     = $this->original_url;
    $json_request["params"]["notify"]["result"] = $this->notify;
    return json_encode($json_request);
  }

  /**
   * Returns the required Array to proxy a request to Waiap
   *
   * @return  Array request ready to proxy to Waiap
   */
  public function toArray()
  {
    $json_request                               = $this->request;
    $json_request["params"]["order"]            = $this->order_id;
    $json_request["params"]["amount"]           = $this->amount;
    $json_request["params"]["currency"]         = $this->currency;
    $json_request["params"]["group_id"]         = $this->group_id;
    $json_request["params"]["original_url"]     = $this->original_url;
    $json_request["params"]["notify"]["result"] = $this->notify;
    return $json_request;
  }
  
  /**
   * Sets order id that will be sent to confirm payments
   * 
   *
   * @param  mixed $order_id Order id which the petition is built around 
   * @return void
   */
  public function setOrderId($order_id){
    if(strlen($order_id) > 12){
      throw new \PWall\Exception\InvalidArgumentException('Order id must not exceed 12 characters');
    }
    $this->order_id = str_pad(strval($order_id), 12, "0", STR_PAD_LEFT);
  }
  
    
  /**
   * Sets amount for the payment, defaults to 0 for backend operations
   *
   * @param  float $amount Amount with decimals that will be charged to customer
   * @return void
   */
  public function setAmount($amount){
    // if($amount <= floatval(0)){
    //   throw new \PWall\Exception\InvalidArgumentException('Order amount must be more than 0');
    // }
    $this->amount = strval($amount * 100);
  }


  /**
   * Sets currency of the sale operation
   *
   * @param   $currency ISO 4217 currency code
   * @return void
   */
  public function setCurrency($currency){
    $this->currency = $currency;
  }
  
  /**
   * Sets customer id for the request
   *
   * @param int $customer_id Customer id group
   * @return void
   */
  public function setGroupId($customer_id){
    $this->group_id = $customer_id;
  }

  /**
   * Sets original url for the request
   *
   * @param string $original_url Base url of commerce
   * @return void
   */
  public function setOriginalUrl($original_url){
    $parsed_url = parse_url($original_url);
    if (!$parsed_url) {
      throw new \PWall\Exception\InvalidArgumentException('Invalid url');
    }
    $this->original_url = $parsed_url["host"];
  }

  /**
   * Sets return url for redirect payment methods
   *
   * @param  $url url to redirect after payment gateway
   * @return void
   */
  public function setNotifyResult($url)
  {
    $this->notify = $url;
  }
    
  /**
   * Check if request is action sale, this can NOT be used to place order
   *
   * @return boolean true if request is for sale action, false otherwise
   */
  public function isActionSale(){
    if(is_array($this->request)
    && array_key_exists("action", $this->request)
    && $this->request["action"] === \PWall\Helper\Constants::PWALL_ACTION_SALE){
      return true;
    }

    return false;
  }

  /**
   * Check if request is and action of express checkbox
   * 
   * @return boolean true if request is for sale action, false otherwise
   */
  public function isActionExpressCheckout(){
    if(is_array($this->request)
    && array_key_exists("params", $this->request) 
    && array_key_exists("express_checkout", $this->request["params"])
    && $this->request["params"]["express_checkout"] == true){
      return true;
    }
    return false;
  }

  /**
   * Check if request is getExtraData action
   * 
   * @return boolean true if request is for sale action, false otherwise
   */
  public function isActionGetExtraData()
  {
    if (
      is_array($this->request)
      && array_key_exists("action", $this->request)
      && $this->request["action"] == true
    ) {
      return true;
    }
    return false;
  }

  /**
   * Check if request is Paypal get cart info action
   * 
   * @return boolean true if request is Paypal get cart info action, false otherwise
   */
  public function isPaypalEcCreateOrder(){
    if(
      is_array($this->request)
      && array_key_exists("params", $this->request)
      && array_key_exists("create_order", $this->request["params"])
      && $this->request["params"]["create_order"] == true
      && array_key_exists("method", $this->request["params"])
      && $this->request["params"]["method"] == "paypal"
      && array_key_exists("express", $this->request["params"])
      && $this->request["params"]["express"] == true
    ){
      return true;
    }
    return false;
  }

  /**
   * Check for update amount in response, to ignore address if any
   * @param  string $message Error message to return
   * @return void
   */
  public function hasUpdateAmount(){
    //$this->request["params"]
    if (
      is_array($this->request)
      && array_key_exists("params", $this->request)
      && array_key_exists("update_amount", $this->request["params"])
    ) {
      return true;
    }
    return false;
  }

/**
   * Set cart info to paypal express checkout
   * @param  string $items      Cart info formatted as Paypal requierements
   * @param  string $is_digital true if all items in the cart are digital, false otherwise
   * @param  string $breakdown  Cart breakdown totals
   * @return void
   */
  public function setPaypalEcCartInfo($items, $is_digital, $breakdown)
  {
    $this->request["params"]["items"]       = $items;
    $this->request["params"]["is_digital"]  = $is_digital;
    $this->request["params"]["breakdown"]   = $breakdown;
  }


}
