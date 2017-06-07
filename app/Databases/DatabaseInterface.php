<?php

namespace app\Databases;


use app\Models\ExchangeRateModel;

interface DatabaseInterface
{
    public function connect();
    public function saveExchangeRate(ExchangeRateModel $exchangeRateModel);

}