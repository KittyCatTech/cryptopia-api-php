<?php
include 'cryptopiaAPI.php';

//---------------------- Sample Code for cryptopia-api-php ----------------------
// Sample code for each function of the api wrapper.
//
// Required files: cryptopiaAPI.php and exchange.php
//
// Put in your API Key and Secret for any 'Private API' calls.
//
// Sample code is indented below each function description bar.
// Uncomment the code you want to run.
// make sure to use the try{ ... } } catch(Exception $e) { echo '' . $e->getMessage() . PHP_EOL;}
// to catch any error message and stop running the rest of the code in the try.


$API_KEY = 'XXXXXXXXXXXXXXXXXXXX';
$API_SECRET = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

try {
   // create a new instance of the API Wrapper
   $ct = New Cryptopia($API_SECRET, $API_KEY); 


   //---------------------------------------------------------------------------
   // getSymbols() - Returns and array of currency pairs and currency pair ids
   //---------------------------------------------------------------------------

      //print_r($ct->getSymbols);

   // returns:
   // Array
   // (
   //     [DOTBTC] => 100
   //     [LTCBTC] => 101
   //     [DOGEBTC] => 102
   //     [POTBTC] => 104
   //     [FTCBTC] => 105
   //     [WSXBTC] => 106
   //     [DARKBTC] => 1101
   //     [RDDBTC] => 1105
   //     [DGBBTC] => 1125
   //     [ARIBTC] => 1142
   //     ...
   // )
   //
   //echo substr(print_r($ct->getSymbols(), true), 0, 213) . "\n    ...\n)\n";
   //echo implode(", ", array_keys($ct->getSymbols())) . PHP_EOL;
   //echo implode(", ", $ct->getSymbols()) . PHP_EOL;
   //foreach (array_slice($ct->getSymbols(), 0, 25) as $key => $value) { echo "('$key': $value) "; }


   //---------------------------------------------------------------------------
   // updatePrices() - Reload the array of prices from the exchange
   // getPrices() - Returns array with high, low, bid, ask, & last price for each currency pair
   //---------------------------------------------------------------------------

      //$ct->updatePrices();
      //print_r($ct->getPrices());

   // returns:
   // Array
   // (
   //     [DOT/BTC] => Array
   //         (
   //             [high] => 2.4E-7
   //             [low] => 1.7E-7
   //             [bid] => 2.0E-7
   //             [ask] => 2.1E-7
   //             [last] => 2.0E-7
   //             [time] =>
   //         )
   //
   //     [DOT/LTC] => Array
   //         (
   //             [high] => 5.556E-5
   //             [low] => 4.601E-5
   //             [bid] => 4.701E-5
   //             [ask] => 5.0E-5
   //             [last] => 4.701E-5
   //             [time] =>
   //         )
   //
   //     [DOT/DOGE] => Array
   //         (
   //             [high] => 1.030055
   //             [low] => 0.92000004
   //             [bid] => 0.93000002
   //             [ask] => 1.013003
   //             [last] => 0.93000001
   //             [time] =>
   //         )
   //
   //     ...
   // )
   //print_r(array_slice($ct->getPrices(), 0, 3));


   //---------------------------------------------------------------------------
   // getBalance() - Returns array of account balance for each crypto currency
   //---------------------------------------------------------------------------

      //print_r($ct->getBalance());

   // returns:
   // Array
   // (
   //     [0] => Array
   //         (
   //             [CurrencyId] => 331
   //             [Symbol] => 1337
   //             [Total] => 0
   //             [Available] => 0
   //             [Unconfirmed] => 0
   //             [HeldForTrades] => 0
   //             [PendingWithdraw] => 0
   //             [Address] =>
   //             [Status] => OK
   //             [StatusMessage] =>
   //             [BaseAddress] =>
   //         )
   //
   //     [1] => Array
   //         (
   //             [CurrencyId] => 99
   //             [Symbol] => CHAO
   //             [Total] => 0
   //             [Available] => 0
   //             [Unconfirmed] => 0
   //             [HeldForTrades] => 0
   //             [PendingWithdraw] => 0
   //             [Address] =>
   //             [Status] => OK
   //             [StatusMessage] =>
   //             [BaseAddress] =>
   //         )
   //
   //     [2] => Array
   //         (
   //             [CurrencyId] => 93
   //             [Symbol] => 42OLD
   //             [Total] => 0
   //             [Available] => 0
   //             [Unconfirmed] => 0
   //             [HeldForTrades] => 0
   //             [PendingWithdraw] => 0
   //             [Address] =>
   //             [Status] => Maintenance
   //             [StatusMessage] => Investigating transaction creation issues
   //             [BaseAddress] =>
   //         )
   //
   //     ...
   // )
   //print_r(array_splice($ct->getBalance(), 0, 3));


   //---------------------------------------------------------------------------
   // getCurrencyBalance($currency) - Returns the balance for specified crypto currency
   //---------------------------------------------------------------------------

      //$my_btc = $ct->GetCurrencyBalance( "BTC" );
      //echo "BTC Balance: " . $my_btc . PHP_EOL;

   // returns:
   // BTC Balance: 0.2


   //---------------------------------------------------------------------------
   // activeOrders() - Returns array of all of your open buy and sell orders
   //---------------------------------------------------------------------------

      //print_r($ct->activeOrders());

   // returns:
   // Array
   // (
   //     [0] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Buy
   //             [price] => 0.00246459
   //             [amount] => 100
   //             [id] => 14469931
   //         )

   //     [1] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Buy
   //             [price] => 0.002
   //             [amount] => 200
   //             [id] => 14469019
   //         )

   //     [2] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Sell
   //             [price] => 0.005
   //             [amount] => 99.58655583
   //             [id] => 14469358
   //         )
   // )


   //---------------------------------------------------------------------------
   // cancelOrder($id) - Cancels a specific order given the order id
   //---------------------------------------------------------------------------

   /*
      // Cancel your sell orders
      $open_orders = $ct->activeOrders();
      if( $open_orders ) { // if there are any open orders
         foreach ( $open_orders as $order) {
            if($order["type"] == "Sell")
               $ct->cancelOrder($order["id"]);
         } 
         sleep(15); // wait for orders to cancel before getting the order book again
      }
   */

   // returns:
   // Order Canceled: 14469358


   //---------------------------------------------------------------------------
   // cancelAll() - Cancels all of your orders
   //---------------------------------------------------------------------------

   /*
      if( $cancelAllMessage = $ct->cancelAll() ) {
         echo $cancelAllMessage;
         // wait for orders to cancel
         sleep(15);
      } else {
         echo "No open orders to cancel.\n";
      }
   */

   // returns:
   // Orders Canceled: 14469931, 14469019, 14469358


   //---------------------------------------------------------------------------
   // buy($symbol, $amount, $price);  - Places a buy order
   //---------------------------------------------------------------------------

   /*
      $trade_pair = "LTCBTC";
      $buy_amt = 10;
      $buy_price = 0.002;
      echo $ct->buy($trade_pair, $buy_amt, $buy_price);
   */

   // returns:
   // Order Placed. OrderId:14462594 FilledOrders:


   //---------------------------------------------------------------------------
   // sell($symbol, $amount, $price); - Places a sell order
   //---------------------------------------------------------------------------

   /*
      $trade_pair = "LTCBTC";
      $sell_amt = 10;
      $sell_price = 0.005;
      echo $ct->sell($trade_pair, $sell_amt, $sell_price);
   */

   // returns:
   // Order Placed. OrderId:14462617 FilledOrders:


   //---------------------------------------------------------------------------
   // marketOrderbook($symbol) - Return an array of all buy/sell orders on the exchanges orderbook for a given currency pair (specified in only capital letter)
   //---------------------------------------------------------------------------

      //print_r($ct->marketOrderbook("LTCBTC"));

   // returns:
   //
   // Array
   // (
   //     [0] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Buy
   //             [price] => 0.00406
   //             [amount] => 0.01
   //         )
   //
   //     [1] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Buy
   //             [price] => 0.00405001
   //             [amount] => 0.05429225
   //         )
   //
   //     ...
   //
   //     [100] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Sell
   //             [price] => 0.00427999
   //             [amount] => 0.43573528
   //         )
   //
   //     [101] => Array
   //         (
   //             [symbol] => LTCBTC
   //             [type] => Sell
   //             [price] => 0.00428
   //             [amount] => 458.79254191
   //         )
   //
   //     ...
   // )
   //print_r(array_slice($ct->marketOrderbook("LTCBTC"), 98, 4));

   //---------------------------------------------------------------------------
   // highestBid($orders [, $depth = 0 [, $type = "Buy"]] )  - Receives an ordered array of orders and return the highest buy price at a specified depth
   //---------------------------------------------------------------------------

   /*
      $trade_pair = "LTCBTC";
      $mkt = $ct->marketOrderbook($trade_pair);
      $bid_depth = 100;  // 100 LTC Deep into the bid offers
      $highest_bid = $ct->highestBid( $mkt, $bid_depth);
      echo "Bid price $bid_depth deep: $highest_bid BTC" . PHP_EOL;
   */

   // returns:
   // Bid price 100 deep: 0.00301 BTC


   //---------------------------------------------------------------------------
   // lowestAsk($offers [, $depth = 0 [, $type = "Sell"]] ) - Receives an ordered array of orders and return the lowest sell price at a specified depth
   //---------------------------------------------------------------------------

   /*
      $trade_pair = "LTCBTC";
      $mkt = $ct->marketOrderbook($trade_pair);
      $ask_depth = 100;  // 100 LTC Deep into the ask offers
      $lowest_ask = $ct->lowestAsk( $mkt, $ask_depth);
      echo "Ask price $ask_depth deep: $lowest_ask BTC" . PHP_EOL;
   */

   // returns:
   // Ask price 100 deep: 0.00428 BTC

} catch(Exception $e) {
   echo '' . $e->getMessage() . PHP_EOL;
}

?>