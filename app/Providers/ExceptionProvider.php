<?php

namespace app\Providers;

use app\Models\ExchangeRateModel;

class ExceptionProvider extends AbstractExchangeProvider
{
    public function getType()
    {
        return ExchangeRateModel::TYPE_BITCOIN_USD;
    }

    public function getExchangeRate() {
        throw new \Exception('to test Exception handling!');
    }

}