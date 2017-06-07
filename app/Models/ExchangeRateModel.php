<?php
/**
 * Created by PhpStorm.
 * User: bbs_m
 * Date: 6/7/2017
 * Time: 12:05 AM
 */

namespace app\Models;


class ExchangeRateModel
{
    const TYPE_EURO_USD = 'euro_usd';
    const TYPE_BITCOIN_USD = 'btc_usd';

    private $type;
    private $providerName;
    private $value;
    private $time;
    private $expirationInterval;

    public function __construct($type, $providerName, $value, $time, $expirationInterval = 5)
    {
        if (!in_array($type, [self::TYPE_EURO_USD, self::TYPE_BITCOIN_USD])) {
            throw new \InvalidArgumentException('Please provide valid exchange rate type!');
        }
        $this->type = $type;
        $this->providerName = $providerName;
        $this->value = $value;
        $this->time = $time;
        $this->expirationInterval = $expirationInterval;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Expiration interval in seconds
     *
     * @return int
     */
    public function getExpirationInterval()
    {
        return $this->expirationInterval;
    }

    public function toJson()
    {
        return json_encode(
            [
                'type' => $this->getType(),
                'provider_name' => $this->getProviderName(),
                'value' => $this->getValue(),
                'time' => $this->getTime(),
                'expiration_interval' => $this->expirationInterval
            ]
        );
    }
}