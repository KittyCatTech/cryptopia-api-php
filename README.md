# cryptopia-api-php
Cryptopia API Wrapper in PHP

## Functions:

* getSymbols() - Returns and array of currency pairs and currency pair ids
* makeStandardSymbol($exchangeSymbolLabel) - Converts currency pairs into only uppercase letters (LTC/BTC or ltc_btc to LTCBTC)
* getExchangeSymbol($standardSymbol) - Recieves the currency pair in only capital letters  and returns the exchanges currency pair id
* getPrices() - Returns array with high, low, bid, ask, & last price for each currency pair
* updatePrices() - Reload the array of prices from the exchange
* getBalance() - Returns array of account balance for each crypto currency 
* getCurrencyBalance($currency) - Returns the balance for specified crypto currency
* activeOrders() - Retuens array of all of your open buy and sell orders
* cancelOrder($id) - Cancels a specific order given the order id
* cancelAll() - Cancels all of your orders
* buy($symbol, $amount, $price);  - Places a buy order (currency pair symbol specified in only capital letter)
* sell($symbol, $amount, $price); - Places a sell order (currency pair symbol specified in only capital letter)
* marketOrderbook($symbol) - Returns an array of all buy/sell orders on the exchanges orderbook for a given currency pair (specified in only capital letter)
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
   $my_btc = $ct->getCurrencyBalance( "BTC" );
   echo "BTC Balance: " . $my_btc . PHP_EOL;
} catch(Exception $e) {
   echo '' . $e->getMessage() . PHP_EOL;
}
?>
```
## Sample Code:

[sample.php](https://github.com/KittyCatTech/cryptopia-api-php/blob/master/sample.php) - Sample code to call each function in the API wrapper.

[samplebuybot.php](https://github.com/KittyCatTech/cryptopia-api-php/blob/master/samplebuybot.php) - Sample Buy Bot.

## License:

[BipCot NoGov Software License](https://github.com/KittyCatTech/cryptopia-api-php/blob/master/LICENSE)


## Development:

Donations are accepted in Monero and BipCoin:

XMR: 49kC7NB3iagZf2T4AhBdL84N9JaugEhvJVJDBEuMEKQSUnrx3xFoDzejpRKiSgX7V1j1im8h8xyRmNXJJSQtBtJS7F25nzs

BIP: bip1WevdQxcaVYr1bRuqEsEqU4vEJ5qFtHsrWANG7hbTYyvTmvTswC8FcX6yAZ2MunWE3Fu1qLpTBVUnf7hDhWpi4BbozDmQJ1
