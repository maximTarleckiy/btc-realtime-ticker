<?php

namespace app\Providers;


use app\Models\ExchangeRateModel;

abstract class HttpExchangeProvider extends AbstractExchangeProvider
{
    private $client;

    public function __construct($name, $expirationInterval)
    {
        parent::__construct($name, $expirationInterval);
        $this->client = new \GuzzleHttp\Client();
    }

    abstract protected function getType();

    abstract protected function getUri();

    /**
     * Parse response and get value
     *
     * @param \Psr\Http\Message\StreamInterface $res
     * @return float|null
     */
    abstract protected function parseResponse(\Psr\Http\Message\StreamInterface $res);

    public final function getExchangeRate()
    {
        $maxAttempts = 3;
        do {
            $maxAttempts--;
echo 'Send http request in' . get_class($this) . '. ' . $maxAttempts . PHP_EOL;
            try {
                $res = $this->client->request('GET', $this->getUri(), ['timeout' => 2]);
            } catch (\Exception $e) {
            }
        } while ($maxAttempts > 0 && !isset($res));

        if ($maxAttempts === 0) {
            throw new \Exception(sprintf('Bad feed response for %s', get_class($this)), 0, $e);
        }

        $value = $this->parseResponse($res->getBody());
        if (!is_float($value)) {
            throw new \Exception(sprintf('%s feed data response is not float!', get_class($this)));
        }

        return (new ExchangeRateModel($this->getType(), $this->getName(), $value, time(), $this->getExpirationInterval()));
    }
}