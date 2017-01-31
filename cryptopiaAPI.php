<?php
include 'exchange.php';

class Cryptopia extends Exchange{
    
   public function __construct($priv, $pub) {
      $this->privateKey = $priv;
      $this->publicKey = $pub;

      $result = json_decode($this->apiCall("GetBalance", array( 'Currency'=> 'BTC' )), true); // There is a bug in the API if you send no parameters it will return Success:true Error: Market not found.
         // Array
         // (
         //    [Success] => 1
         //    [Message] =>
         //    [Data] =>
         //    [Error] => Market not found.
         // )
      // print_r($result);
      if( $result['Success'] != "true" ) {
         throw new Exception("Can't Connect to Cryptopia, Error: " . $result['Error'] );
         return false;
      }
      return true;
   }

   private function apiCall($method, array $req = array()) {
      $public_set = array( "GetCurrencies", "GetTradePairs", "GetMarkets", "GetMarket", "GetMarketHistory", "GetMarketOrders" );
      $private_set = array( "GetBalance", "GetDepositAddress", "GetOpenOrders", "GetTradeHistory", "GetTransactions", "SubmitTrade", "CancelTrade", "SubmitTip" );
      static $ch = null;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptopia.co.nz API PHP client; FreeBSD; PHP/'.phpversion().')');
      if ( in_array( $method ,$public_set ) ) {
         $url = "https://www.cryptopia.co.nz/api/" . $method;
         if ($req) { foreach ($req as $r ) { $url = $url . '/' . $r; } }
         curl_setopt($ch, CURLOPT_URL, $url );
      } elseif ( in_array( $method, $private_set ) ) {
         $url = "https://www.cryptopia.co.nz/Api/" . $method;
         $nonce = explode(' ', microtime())[1];
         $post_data = json_encode( $req );
         $m = md5( $post_data, true );
         $requestContentBase64String = base64_encode( $m );
         $signature = $this->publicKey . "POST" . strtolower( urlencode( $url ) ) . $nonce . $requestContentBase64String;
         $hmacsignature = base64_encode( hash_hmac("sha256", $signature, base64_decode( $this->privateKey ), true ) );
         $header_value = "amx " . $this->publicKey . ":" . $hmacsignature . ":" . $nonce;
         $headers = array("Content-Type: application/json; charset=utf-8", "Authorization: $header_value");
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_URL, $url );
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $req ) );
      }
          // run the query
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); // Do Not Cache
      $res = curl_exec($ch);
      if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
      return $res;
   }

   // Some API calls require TradePairId rather than the TradePair so this should store the TradePairId
   public function setSymbols() {
      $result = json_decode($this->apiCall("GetTradePairs", array() ), true);

      if( $result['Success'] == "true" ) {
         $json = $result['Data'];
      } else {
         throw new Exception("Can't get symbols, Error: " . $result['Error'] );
      }
      foreach($json as $pair) {
         // creates associative array of key: StandardSymbol (i.e. BTCUSD) value: ExchangeSymbol (i.e. btc_usd)
         $this->symbols[ $this->makeStandardSymbol( $pair["Label"] ) ] = $pair["Id"];
      }
    }
    
   public function updatePrices() {
      $result = json_decode($this->apiCall("GetMarkets", array() ), true);
      if( $result['Success'] == "true" ) {
         $json = $result['Data'];
      } else {
         throw new Exception("Can't get markets, Error: " . $result['Error'] );
      }
      foreach($json as $pair) {
         $this->prices[$pair['Label']]['high'] = $pair['High'];
         $this->prices[$pair['Label']]['low']  = $pair['Low'];
         $this->prices[$pair['Label']]['bid']  = $pair['BidPrice'];
         $this->prices[$pair['Label']]['ask']  = $pair['AskPrice'];
         $this->prices[$pair['Label']]['last'] = $pair['LastPrice'];
         $this->prices[$pair['Label']]['time'] = '';  // not available on Cryptopia
      }
   }

   // @todo add setBalance

   Public function getBalance() {
      $result = $this->apiCall("GetBalance", array('Currency'=> "") ); // "" for All currency balances
      $result = json_decode($result, true);
      if( $result['Success'] == "true" ) {
         // @todo ADD CODE TO REFORMAT Array to standard
         return $result['Data'];
      } else {
         throw new Exception("Can't get balances, Error: " . $result['Error'] );
      }
   }

   Public function getCurrencyBalance( $currency ) {
      $result = $this->apiCall("GetBalance", array( 'Currency'=> $currency ) );
      $result = json_decode($result, true);
      if( $result['Success'] == "true" ) {
         return $result['Data'][0]['Total'];
      } else {
         throw new Exception("Can't get balance, Error: " . $result['Error'] );
      }
   }

   // currency pair $symbol should be in standard Format not exchange format
   public function activeOrders( $symbol = "")
   {
      if($symbol == "") {
         $apiParams = array( 'TradePairId'=>"" );
      } else {
         $apiParams = array( 'TradePairId'=>$this->getExchangeSymbol($symbol) );       
      }
      $myOrders = json_decode($this->apiCall("GetOpenOrders", $apiParams), true);  
      //print_r($myOrders);
      // There is a bug in the API if you send no parameters it will return Success:true Error: Market not found.
         // Array
         // (
         //    [Success] => 1
         //    [Message] =>
         //    [Data] =>
         //    [Error] => Market not found.
         // )

      $orders = array();
      $price = array();  // sort by price
      if( $myOrders['Success'] == "true" && $myOrders['Error'] == "") {
         foreach ($myOrders['Data'] as $order) {
            $orderSymbol = $this->makeStandardSymbol($order["Market"]); // convert to standard format currency pair
            $orders[] = ["symbol"=>$orderSymbol, "type"=>$order["Type"], "price"=>$order["Rate"],
                         "amount"=>$order["Remaining"], "id"=>$order["OrderId"] ];
            if ($order["Type"] == "Sell") {
               $price[] = 0 - $order['Rate'];  // lowest ask price if first
            } else {
               $price[] = $order['Rate'];
            }
         }
         if($orders) // If there are any orders
            array_multisort($price, SORT_DESC, $orders); // sort orders by price
      } else {
         throw new Exception("Can't get active orders, Error: " . $myOrders['Error'] );
      }
      return $orders;
   }

   public function permissions() {

   }

   public function cancelOrder($id) {
      $result = $this->apiCall("CancelTrade", array( 'Type'=>"Trade", 'OrderId'=>$id ));
      $result = json_decode($result, true);
      if( $result['Success'] == "true" ) {
         echo "Orders Canceled: " . implode( ", ", $result['Data']) . "\n";
      } else {
          throw new Exception("Can't Cancel Order # $id, Error: " . $result['Error']);
      }
   }

   public function cancelAll() {
      if(!$this->activeOrders()) return false;  // "No open orders to cancel.\n"
      $result = $this->apiCall("CancelTrade", array( 'Type'=>"All" ));
      $result = json_decode($result, true);
      if( $result['Success'] == "true" ) {
         return "Orders Canceled: " . implode( ", ", $result['Data']) . "\n";
      } else {
          throw new Exception("Can't Cancel All Orders, Error: " . $result['Error']);
      }
   }

   public function orderStatus($id) {

   }

   public function placeOrder($symbol, $amount, $price, $side) {
      $result = $this->apiCall("SubmitTrade", array( 'Type'=> $side, 'TradePairId'=> $this->getExchangeSymbol($symbol), 
                        'Rate'=> number_format((float)$price, 8, '.', ''), 'Amount'=> number_format((float)$amount, 8, '.', '') ) );
      $result = json_decode($result, true);
      if( $result['Success'] == "true" ) {
         return "Order Placed. OrderId:" .$result['Data']['OrderId'] . 
                 " FilledOrders: " . implode( ", ", $result['Data']['FilledOrders']) . "\n";
      } else {
         throw new Exception("Can't Place Order, Error: " . $result['Error'] ); //*** die instead of echo
      }
   }

   public function buy($symbol, $amount, $price) {
     return $this->placeOrder($symbol, $amount, $price, 'Buy');
   }

   public function sell($symbol, $amount, $price) {
     return $this->placeOrder($symbol, $amount, $price, 'Sell');
   }

   public function marketOrderbook($symbol)
   {
      $mktOrders = json_decode($this->apiCall("GetMarketOrders", array('TradePairId'=>$this->getExchangeSymbol($symbol))), true);
      unset($orders);
      if( $mktOrders['Success'] == "true" && $mktOrders['Error'] == "") {
         //print_r($mktOrders);
         foreach ($mktOrders['Data'] as $orderType => $order) {
            foreach($order as $ordersByType) {
               // $standardSymbol = $this->getStandardSymbol($symbol);  // @todo not yet implemented
               $orders[] = ["symbol"=>$symbol, "type"=>$orderType, "price"=>$ordersByType["Price"],
                            "amount"=>$ordersByType["Volume"] ];
            }
         }
      } else {
         throw new Exception("Can't get orderbook, Error: " . $mktOrders['Error'] );
      }
      return $orders;
   }   

}

?>