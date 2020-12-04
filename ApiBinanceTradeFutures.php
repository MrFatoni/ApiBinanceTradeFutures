<?php

class BinanceTradeFutures
{
    public $btc_value = 0.00;
    protected $base = "https://fapi.binance.com/fapi/", $api_key, $api_secret;

    public function __construct($api_key, $api_secret)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    public function buy($symbol, $quantity, $price, $type = "LIMIT", $timeInForce = "GTC")
    {
        return $this->order("BUY", $symbol, $quantity, $price, $type, $timeInForce);
    }

    public function sell($symbol, $quantity, $price, $type = "LIMIT", $timeInForce = "GTC")
    {
        return $this->order("SELL", $symbol, $quantity, $price, $type, $timeInForce);
    }

    public function sellStopMarket($symbol, $quantity, $price, $stopPrice)
    {
        //timeInForce, quantity, price, stopPrice
        $opt = [
            "symbol" => $symbol,
            "side" => "SELL",
            "stopPrice" => $stopPrice,
            "type" => "STOP_LOSS",
            //            "price" => $price,
            "quantity" => $quantity,
            //            "timeInForce" => "GTC",
            "recvWindow" => 50000 //60000
        ];
        return $this->signedRequest("v3/order", $opt, "POST");
    }

    public function sellStopLimit($symbol, $quantity, $price, $stopPrice)
    {
        //timeInForce, quantity, price, stopPrice
        $opt = [
            "symbol" => $symbol,
            "side" => "SELL",
            "stopPrice" => $stopPrice,
            "type" => "STOP_LOSS_LIMIT",
            "price" => $price,
            "quantity" => $quantity,
            "timeInForce" => "GTC",
            "recvWindow" => 50000 //60000
        ];
        return $this->signedRequest("v3/order", $opt, "POST");
    }

    public function buyStopLimit($symbol, $quantity, $price, $stopPrice)
    {
        //timeInForce, quantity, price, stopPrice
        $opt = [
            "symbol" => $symbol,
            "side" => "BUY",
            "stopPrice" => $stopPrice,
            "type" => "STOP_LOSS_LIMIT",
            "price" => $price,
            "quantity" => $quantity,
            "timeInForce" => "GTC",
            "recvWindow" => 50000 // 60000
        ];
        return $this->signedRequest("v3/order", $opt, "POST");
    }

    public function cancel($symbol, $orderid)
    {
        $url = "v1/order";
        $params = ["symbol" => $symbol, "orderId" => $orderid];

        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\nX-MBX-APIKEY: {$this->api_key}\r\n";
        //        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');
        //        $params['timestamp'] = (time()*1000)-BinanceTrade::$tambahanDetik;
        //        global $tambahanDetik;
        //        $milliseconds = round(microtime(true) * 1000);
        $params['timestamp'] = (time() * 1000);

        $query = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query, $this->api_secret);
        $urlcurl = "{$this->base}{$url}?{$query}&signature={$signature}";
        //        return json_decode($this->http_request_old($endpoint, $headers), true);
        $data  = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlcurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        if (strpos($urlcurl, 'v1/order') == true) {
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return json_decode($content, true);
    }

    public function cancelBatch($symbol, $orderid)
    {
        $url = "v1/batchOrders";
        $params = ["symbol" => $symbol, "orderIdList" => $orderid];

        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\nX-MBX-APIKEY: {$this->api_key}\r\n";
        //        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');
        //        $params['timestamp'] = (time()*1000)-BinanceTrade::$tambahanDetik;
        //        global $tambahanDetik;
        //        $milliseconds = round(microtime(true) * 1000);
        $params['timestamp'] = (time() * 1000);

        $query = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query, $this->api_secret);
        $urlcurl = "{$this->base}{$url}?{$query}&signature={$signature}";
        //        return json_decode($this->http_request_old($endpoint, $headers), true);
        $data  = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlcurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        if (strpos($urlcurl, 'v1/batchOrders') == true) {
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return json_decode($content, true);
    }

    public function orderStatus($symbol, $orderid)
    {
        //        return $this->signedRequest("v3/order", ["symbol" => $symbol, "orderId" => $orderid]);

        $url = "v1/order";
        $params = ["symbol" => $symbol, "orderId" => $orderid];

        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\nX-MBX-APIKEY: {$this->api_key}\r\n";
        //        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');
        //        $params['timestamp'] = (time()*1000)-BinanceTrade::$tambahanDetik;
        //        global $tambahanDetik;
        //        $milliseconds = round(microtime(true) * 1000);
        $params['timestamp'] = (time() * 1000);

        $query = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query, $this->api_secret);
        $urlcurl = "{$this->base}{$url}?{$query}&signature={$signature}";
        //        return json_decode($this->http_request_old($endpoint, $headers), true);
        $data  = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlcurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        if (strpos($urlcurl, 'v1/order') == true) {
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return json_decode($content, true);
    }

    public function openOrdersAll()
    {
        return $this->signedRequest("v1/openOrders");
    }

    public function currentPosition()
    {
        return $this->signedRequest("v1/positionRisk");
    }


    public function openOrders($symbol)
    {
        return $this->signedRequest("v1/openOrders", ["symbol" => $symbol]);
    }

    public function orders($symbol, $limit = 500)
    {
        return $this->signedRequest("v3/allOrders", ["symbol" => $symbol, "limit" => $limit]);
    }

    public function ordersAll($limit = 30)
    {
        return $this->signedRequest("v3/allOrders", ["limit" => $limit]);
    }

    public function changeLeverage($symbol, $leverage)
    {
        // echo "In";
        return $this->signedRequestPost("v1/leverage", ["symbol" => $symbol, "leverage" => (int) $leverage], "POST");
    }


    public function trades($symbol)
    {
        return $this->signedRequest("v3/myTrades", ["symbol" => $symbol]);
    }

    public function prices()
    {
        return $this->priceData($this->request("v1/ticker/allPrices"));
    }

    public function bookPrices()
    {
        return $this->bookPriceData($this->request("v1/ticker/allBookTickers"));
    }

    public function account()
    {
        return $this->signedRequest("v3/account");
    }

    public function getBalance($coin)
    {

        $acc = $this->signedRequest("v3/account");
        $arr = $acc["balances"];
        foreach ($arr as $x) {
            if ($x["asset"] == $coin) {
                return $x;
            }
        }
        return array();
    }

    public function getBalanceStable()
    {

        $arrStable = array("USDT", "USDC", "TUSD", "PAX", "USDB", "USDS");

        $acc = $this->signedRequest("v3/account");
        $arr = $acc["balances"];
        $simpan = array();
        foreach ($arr as $x) {
            if (in_array($x["asset"], $arrStable)) {
                $simpan[$x["asset"]] = $x["free"];
            }
        }
        return $simpan;
    }

    public function depth($symbol)
    {
        return $this->request("v1/depth", ["symbol" => $symbol]);
    }

    public function balances($priceData = false)
    {
        $balance = $this->signedRequest("v3/account", array("recvWindow" => 59000));
        if (empty($balance['balances'])) {
            $balance = $this->signedRequest("v3/account", array("recvWindow" => 59000));
            if (empty($balance['balances'])) {
                LineFinder::push("Binance Balances Error", "Binance Balances Error " . leap_mysqldate() . "\n\r");
                KoneksiError::simpan("binance", $balance);
                exit(json_encode($balance));
            }
        }
        return $this->balanceData($balance, $priceData);
    }

    private function request($url, $params = [])
    {
        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\n";
        $query = http_build_query($params, '', '&');
        return json_decode($this->http_request($this->base . $url . '?' . $query, $headers), true);
    }

    public function http_request($url, $headers, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (strpos($url, 'v1/order') == true) {
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return $content;
    }

    public function http_request_old($url, $headers, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return $content;
    }

    //    public static $tambahanDetik = 15000;

    private function signedRequest($url, $params = [])
    {
        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\nX-MBX-APIKEY: {$this->api_key}\r\n";
        //        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');
        //        $params['timestamp'] = round(microtime(true));
        //        global $tambahanDetik;
        //        $milliseconds = round(microtime(true) * 1000);
        $params['timestamp'] = (time() * 1000);
        // if ($_GET["debug"])
            //    pr($params);

        $query = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query, $this->api_secret);
        $endpoint = "{$this->base}{$url}?{$query}&signature={$signature}";

        // if ($_GET["debug"])
            // pr($endpoint);
        $signed = json_decode($this->http_request($endpoint, $headers), true);

        // if ($_GET["debug"]) {
            // echo "signedRequest";
            // pr($signed);
        // }
        return $signed;
    }

    private function signedRequestPost($url, $params = [])
    {
        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\nX-MBX-APIKEY: {$this->api_key}\r\n";
        //        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');
        //        $params['timestamp'] = round(microtime(true));
        //        global $tambahanDetik;
        //        $milliseconds = round(microtime(true) * 1000);
        $params['timestamp'] = (time() * 1000);
        // if ($_GET["debug"])
            //    pr($params);

        $query = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query, $this->api_secret);
        $endpoint = "{$this->base}{$url}?{$query}&signature={$signature}";

        // if ($_GET["debug"])
            // pr($endpoint);
        $signed = json_decode($this->http_request($endpoint, $headers,$params), true);

        // if ($_GET["debug"]) {
            // echo "signedRequest";
            // pr($signed);
        // }
        return $signed;
    }

    //ada GTC, FOK, IOC
    public function order($side, $symbol, $quantity, $price, $type = "LIMIT", $timeInForce = "GTC", $stopPrice = 0)
    {
        $opt = [
            "symbol" => $symbol,
            "side" => $side,
            "type" => $type,
            "price" => $price,
            "quantity" => $quantity,
            "timeInForce" => $timeInForce,
            "recvWindow" => 59000 // 60000 //recvWindow 10000000
        ];

        if ($type == "MARKET") {
            $opt = [
                "symbol" => $symbol,
                "side" => $side,
                "type" => $type,
                "quantity" => $quantity,
                "recvWindow" => 59000
            ];
        }

        if ($type == "MARKET_CLOSE") {
            $opt = [
                "symbol" => $symbol,
                "side" => $side,
                "type" => "MARKET",
                "quantity" => $quantity,
                "reduceOnly" => "true",
                "recvWindow" => 59000
            ];
        }

        if ($type == "LIMIT") {
            $opt = [
                "symbol" => $symbol,
                "side" => $side,
                "type" => $type,
                "quantity" => $quantity,
                "price" => $price,
                "timeInForce" => $timeInForce,
                // "reduceOnly" => "true",
                //                "origClientOrderId" => "test",
                "recvWindow" => 59000
            ];
        }

        if ($type == "STOP") {
            $opt = [
                "symbol" => $symbol,
                "side" => $side,
                "type" => $type,
                "quantity" => $quantity,
                "price" => $price,
                "stopPrice" => $stopPrice,
                "reduceOnly" => "true",
                "recvWindow" => 59000
            ];
        }

        if ($type == "STOP_MARKET") {
            $opt = [
                "symbol" => $symbol,
                "side" => $side,
                "type" => $type,
                "quantity" => $quantity,
                "stopPrice" => $stopPrice,
                // "reduceOnly" => "true",
                "recvWindow" => 59000
            ];
        }

        if ($type == "TAKE_PROFIT_MARKET") {
            $opt = [
                "symbol" => $symbol,
                "side" => $side,
                "type" => $type,
                "quantity" => $quantity,
                "stopPrice" => $stopPrice,
                // "reduceOnly" => "true",
                "recvWindow" => 59000
            ];
        }



        return $this->signedRequest("v1/order", $opt, "POST");
    }



    //1m,3m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M
    public function candlesticks($symbol, $interval = "5m", $limit = 500)
    {
        return $this->request("v1/klines", ["symbol" => $symbol, "interval" => $interval, "limit" => $limit]);
    }

    private function balanceData($array, $priceData = false)
    {
        if ($priceData) $btc_value = 0.00;
        $balances = [];
        foreach ($array['balances'] as $obj) {
            $asset = $obj['asset'];
            $balances[$asset] = ["available" => $obj['free'], "onOrder" => $obj['locked'], "btcValue" => 0.00000000];
            if ($priceData) {
                if ($obj['free'] < 0.00000001) continue;
                if ($asset == 'BTC') {
                    $balances[$asset]['btcValue'] = $obj['free'];
                    $btc_value += $obj['free'];
                    continue;
                }
                $btcValue = number_format($obj['free'] * $priceData[$asset . 'BTC'], 8, '.', '');
                $balances[$asset]['btcValue'] = $btcValue;
                $btc_value += $btcValue;
            }
        }
        if ($priceData) {
            uasort($balances, function ($a, $b) {
                return $a['btcValue'] < $b['btcValue'];
            });
            $this->btc_value = $btc_value;
        }
        return $balances;
    }

    private function bookPriceData($array)
    {
        $bookprices = [];
        foreach ($array as $obj) {
            $bookprices[$obj['symbol']] = [
                "bid" => $obj['bidPrice'],
                "bids" => $obj['bidQty'],
                "ask" => $obj['askPrice'],
                "asks" => $obj['askQty']
            ];
        }
        return $bookprices;
    }

    private function priceData($array)
    {
        $prices = [];
        foreach ($array as $obj) {
            $prices[$obj['symbol']] = $obj['price'];
        }
        return $prices;
    }
}
