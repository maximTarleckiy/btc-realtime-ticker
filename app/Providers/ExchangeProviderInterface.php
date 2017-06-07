<?php

namespace app\Providers;


use app\Models\ExchangeRateModel;

interface ExchangeProviderInterface
{
    /**
     * @param string  $name
     * @param integer $expirationInterval (in seconds)
     */
    public function __construct($name, $expirationInterval);

    /**
     * @return ExchangeRateModel
     */
    public function getExchangeRate();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getExpirationInterval();
}