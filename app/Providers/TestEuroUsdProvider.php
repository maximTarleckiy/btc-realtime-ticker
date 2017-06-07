<?php

namespace app\Providers;


use app\Models\ExchangeRateModel;

class TestEuroUsdProvider extends AbstractExchangeProvider
{
    public function getExchangeRate()
    {
        return (new ExchangeRateModel(ExchangeRateModel::TYPE_EURO_USD, $this->getName(), 0.7, time(), $this->getExpirationInterval()));
    }
}