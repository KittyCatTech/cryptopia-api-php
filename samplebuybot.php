<?php
include 'cryptopiaAPI.php';

//---------------------- SAMPLE BUY BOT

$API_KEY = 'XXXXXXXXXXXXXXXXXXX';
$API_SECRET = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

$trade_pair = "XMRBTC";
$bid_depth = 3;         // ignore orders smaller than this Qty
$bankroll_percent = 40; // max % of bankroll per buy order
$exclude_amt = 0.00;    // keep this much BTC in your account    
$max_buy = 100;         // max amount to buy 
$min_buy = .1;          // min amount to buy

try {
   $ct = New Cryptopia($API_SECRET, $API_KEY);

   $my_btc = $ct->GetCurrencyBalance( "BTC" );
   echo "BTC Balance Total: " . $my_btc . PHP_EOL;

   echo "My highest bid 0 deep: " . $my_highest_bid = $ct->highestBid( $ct->activeOrders($trade_pair)) . " BTC" . PHP_EOL;

   echo "Market highest bid $bid_depth deep: " . $highest_bid = $ct->highestBid( $ct->marketOrderbook($trade_pair), $bid_depth) . " BTC" . PHP_EOL;

   //----- If not outbid
   if ($my_highest_bid = 0 || $my_highest_bid != $highest_bid) {

      // Cancel All orders
      if( $cancelAllMessage = $ct->cancelAll() ) {
         echo $cancelAllMessage;
         // wait for orders to cancel
         sleep(15);
      } else {
         echo "No open orders to cancel.\n";
      }

      $mkt_offers = $ct->marketOrderbook($trade_pair);
      $highest_bid = $ct->highestBid( $mkt_offers, $bid_depth);
      echo "Market highest bid $bid_depth deep: " . $highest_bid . " BTC" . PHP_EOL;

      // Get Buy  Amount (% of bankroll divided by price w/fee)
      $buy_amt = $bankroll_percent / 100 * ($my_btc - $exclude_amt) / (($highest_bid + 0.00000001) * 1.002);
      echo "Buy Amount: $buy_amt\n";

      $range_vol = $ct->volume($mkt_offers, ($highest_bid * 0.95), ($highest_bid + 0.00000001));
      echo "Amount of orders to outbid within 5% of price: $range_vol\n";
      if (($range_vol * 0.9) < $buy_amt) $buy_amt = $range_vol * 0.9;

      if($buy_amt > $max_buy) $buy_amt = $max_buy;

      echo "Buy " . number_format((float)$buy_amt, 8, '.', '') . " at ". number_format((float)($highest_bid + 0.00000001), 8, '.', '') . "\n";

      //----- Place Buy Orders
      if ($buy_amt > $min_buy)
         $ct->buy($trade_pair, $buy_amt, ($highest_bid + 0.00000001));
      else echo "Buy Amount is too small.\n";

   } else {
      echo "You are not outbid.\n\n"; 
   }
} catch(Exception $e) {
   echo '' . $e->getMessage() . PHP_EOL;
}

?>