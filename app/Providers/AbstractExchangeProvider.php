<?php

namespace app\Providers;


abstract class AbstractExchangeProvider implements ExchangeProviderInterface
{
    private $name;
    private $expirationInterval;

    public function __construct($name, $expirationInterval)
    {
        $this->name = $name;
        $this->expirationInterval = $expirationInterval;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getExpirationInterval()
    {
        return $this->expirationInterval;
    }
}