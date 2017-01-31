# cryptopia-api-php
Cryptopia API Wrapper in PHP

## Functions:

* getSymbols() - Returns and array of currency pairs and currency pair ids
* makeStandardSymbol($exchangeSymbolLabel) - Converts currency pairs into only uppercase letters (LTC/BTC or ltc_btc to BTCUSD)
* getExchangeSymbol($standardSymbol) - Recieves the currency pair in only capital letters  and returns the exchanges currency pair id
* getPrices() - Returns array with high, low, bid, ask, & last price for each currency pair
* updatePrices() - Reload the array of prices from the exchange
* getBalance() - Returns array of account balance for each crypto currency 
* getCurrencyBalance($currency) - Returns the balance for specified cryptocurrency
* activeOrders() - Retuens array of all of your open buy and sell orders
* cancelOrder($id) - Cancels a specific order given the order id
* cancelAll() - Cancels all of your orders
* buy($symbol, $amount, $price);  - Places a buy order
* sell($symbol, $amount, $price); - Places a sell order
* marketOrderbook($symbol) - Return an array of all buy/sell orders on the exchanges orderbook for a given currency pair (specified in only capital letter)
* highestBid($orders, $depth default:0, $type default:"Buy"))  - Recieves an ordered array of orders and return the highest buy price at a specified depth
* LowestAsk($offers, $depth default:0, $type default:"Sell") - Recieves an ordered array of orders and return the lowest sell price at a specified depth

## Usage:

```vim
<?php
include 'cryptopiaAPI.php';

$API_KEY = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
$API_SECRET = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
try {
   $ct = New Cryptopia($API_SECRET, $API_KEY);
   $my_btc = $ct->GetCurrencyBalance( "BTC" );
   echo "BTC Balance: " . $my_btc . PHP_EOL;
} catch(Exception $e) {
   echo '' . $e->getMessage() . PHP_EOL;
}
?>
```

## License:

[BipCot NoGov Software License](https://github.com/KittyCatTech/cryptopia-api-php/blob/master/LICENSE)
