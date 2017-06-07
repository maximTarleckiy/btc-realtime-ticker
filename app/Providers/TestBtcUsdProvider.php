<?php
/**
 * Created by PhpStorm.
 * User: bbs_m
 * Date: 6/7/2017
 * Time: 3:11 PM
 */

namespace app\Providers;


use app\Models\ExchangeRateModel;

class TestBtcUsdProvider extends AbstractExchangeProvider
{

    public function getExchangeRate()
    {
        return (new ExchangeRateModel(ExchangeRateModel::TYPE_BITCOIN_USD, $this->getName(), 1000, time(), $this->getExpirationInterval()));
    }
}