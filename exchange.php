<?php

abstract class Exchange
{
   // ----- Variables -----

   // Each exchanges class construction function should set these and test the connection to the API
   private $privateKey = '';    // API Secret
   private $publicKey = '';     // API Key

   /**
    * Symbols is an array of all the trading pairs on a given exchange.
    *
    * i.e. array("DOT/BTC", "LTC/BTC", "DOGE/BTC", "POT/BTC", "FTC/BTC", "WSX/BTC", "DARK/BTC", ...)
    * It is assigned in the setSymbols() function and retrieved with the getSymbols() function.
    * note: these can be different for different exchanges: "LTC/BTC" "LTCBTC" "ltc_btc"
    * @todo Format functions or lookup tables need to be made for each exchange to standardize the format.
    *       getStandardSymbol($symbol) and getExchangeSymbol($symbol)
    *
    * @var array of strings
    */
   protected $symbols = array(); //Trading Pairs
   
   /**
    * Prices are the current and 24hr market prices for each trading pair.
    *
    * Each trading pair has an array with keys: high, low, bid, ask, last, and time.
    * i.e. {"ETHBTC": {"high":  0, "low": 0, "bid": 0, "ask": 0, "last": 0, "time": 0},
    *        ... ,
    *       "XMRBTC": {"high":  0, "low": 0, "bid": 0, "ask": 0, "last": 0, "time": 0}
    *      }
    * @todo the updatePrices() function for each exchange should use makeStandardSymbol($exchangeSymbol) to get the key for each currency pair array
    * note: trading pair symbol could be different but parameters these are the same across all exchanges
    * It is assigned in the updatePrices() function and retrieved with the getPrices() function.
    *
    * @var array of arrays
    */
   protected $prices = array();

   /**
    * Balances is an array of all currencies and assets.
    *
    * Each currency has an array with keys: amount, available, etc
    * @todo Implement updateBalance() function to set the balance for each exchange.
    *       Should use makeStandardSymbol($exchangeSymbol) to get the key for each currency pair array
    * @var array of arrays
    */
   protected $balances = array();


   // ----- Get and Set Functions -----

   public function getSymbols() {
      if(!$this->symbols) $this->setSymbols();
      return $this->symbols;
   }
   // Can be used for currencies and currency pairs to put in only uppercase letters
   // note: $exchangeSymbol must be have the base currency first and quote currency second (i.e. eth_btc Not btc_eth). The quote currency is usually BTC, USD, or CNY.
   public function makeStandardSymbol($exchangeSymbol) {
      // Convert to Uppercase and remove nonletters.
      return preg_replace('/[^A-Z]/', '', strtoupper ( $exchangeSymbol )); 
   }
   // @todo not implemented for all exchanges, setSymbols() must create associative array $this->symbols with keys as standard symbols and  values as exchange symbols for this function to work.
   public function getExchangeSymbol($standardSymbol) {

      //if($standardSymbol == '') return ''; // used to get all symbols

      if(!$this->symbols) $this->setSymbols();

      if( isset($this->symbols[$standardSymbol]) ) {
         return $this->symbols[$standardSymbol];
      } else {
         throw new Exception("Can't find symbol '" . $standardSymbol ."'" );
      }
   }
    
   public function getPrices() {
      return $this->prices;
   }

   // @todo the updateBalance() function for each exchange should use makeStandardSymbol($exchangeSymbol) to get the key for each currency pairs array
   public function getBalance() { // this function is overridden until updateBalance() is implemented
      // if(!isset($this.balances)) { updateBalance(); }  // Not implemented for all exchanges yet
      return $this->balances;
   }
   Public function getCurrencyBalance($currency) {
      // @todo Update if not isset()
      if(isset($this->balances[$curency]['amount'])) {
         return $this->balances[$curency]['amount'];
      } 
   }

   // @todo not implemented for all exchanges, setSymbols() must create associative array $this->symbols with keys as standard symbols and  values as exchange symbols for this function to work.
   abstract public function setSymbols();

   // abstract public function getFee($symbol);             // @todo Not implemented for ct exchange yet

   // @todo the updatePrices() function for each exchange should use makeStandardSymbol($exchangeSymbol) to get the key for each currency pairs array
   abstract public function updatePrices();

   // abstract public function updateBalance();  // Not implemented for all exchanges yet

   // @todo ??? allow passing a $symbol for all exchanges?
   abstract public function activeOrders();

   abstract public function permissions();

   abstract public function cancelOrder($id);
   // is function should be overridden if exchange has cancel all function call
   // @todo write generalized cancel all that loops through active orders, see btceAPI
   abstract public function cancelAll();
   
   abstract public function orderStatus($id);

   // This should create a buy / sell order at the exchange for [amount] of [asset] at [price] and return the orderId
   // @todo should implement getExchangeSymbol($standardSymbol) for each exchange
   abstract public function buy($symbol, $amount, $price);   // some exchanges may have additional parameters
   abstract public function sell($symbol, $amount, $price);  // some exchanges may have additional parameters


   // ----- Data Calculation functions -----

   // note: assumes buy offers are in descending order by price
   public function highestBid($offers, $depth = 0, $type = "Buy") {
      $total_amount  = 0;
      $highest_bid = 0;
      if($offers)   // if there are any offers
      foreach( $offers as $item )
      {  
         if( $item["type"] == $type ) { 
            //echo "type: " . $item["type"] . " price: " . $item['price'] . " amount: " .$item['amount'] . "\n";
            $total_amount += floatval($item['amount']);

            if( $highest_bid == 0 && $total_amount >= $depth ) // Go $1000 deep into the bids
            {
               $highest_bid = $item['price'];
               break;
            }
         }
      }
      return $highest_bid;
   }

   // note: assumes sell offers are in accenting order by price
   public function LowestAsk($offers, $depth = 0, $type = "Sell") {
      return $this->highestBid($offers, $depth, $type);
   }

   // takes an array of of offers/orders and returns the volume within a price range
   public function volume($offers, $lowerPriceLimit = 0, $upperPriceLimit = PHP_FLOAT_MAX) {
      $total_vol = 0;
      foreach( $offers as $item )
      {  
         //echo "price: " . $item['Price'] . " amount: " .$item['Volume'] . "\n";
         if( $item['price'] >= $lowerPriceLimit &&  $item['price'] <= $upperPriceLimit ) {
            $total_vol += floatval($item['amount']);
         }
      }
      return $total_vol;
   }

} // end of class Exchange

?>