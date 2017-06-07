<?php

namespace app\Providers;

use app\Models\ExchangeRateModel;

class CoindeskBitcoinExchangeProvider extends HttpExchangeProvider {

    public function getType()
    {
        return ExchangeRateModel::TYPE_BITCOIN_USD;
    }

    protected function parseResponse(\Psr\Http\Message\StreamInterface $body)
    {
        $data = json_decode($body, true);
        if (!isset($data['bpi']['USD']['rate_float'])) {
            return;
        }

        return $data['bpi']['USD']['rate_float'];
    }

    protected function getUri()
    {
        return 'http://api.coindesk.com/v1/bpi/currentprice/USD.json';
    }
}