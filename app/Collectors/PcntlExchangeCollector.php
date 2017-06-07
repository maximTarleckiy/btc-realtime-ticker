<?php

namespace app\Collectors;


use app\Databases\DatabaseInterface;
use app\Providers\ExchangeProviderInterface;

class PcntlExchangeCollector
{
    private $providers;

    private $ws_connection;

    /**
     * @var DatabaseInterface
     */
    private $db;

    public function __construct($providers, DatabaseInterface $db = null)
    {
        if (!function_exists('pcntl_fork')) {
            throw new \Exception('PCNTL extension is not available!');
        }
        $this->providers = $providers;
        $this->db = $db;

    }

    public function collectData()
    {
        // for debug only
        if ($this->db) {
            $this->db->connect();
        }

        $pids = [];
        foreach ($this->providers as $name => $provider) {
            $pids[$name] = pcntl_fork();

            if ($pids[$name] === -1) {
                exit; // failed to fork
            } elseif ($pids[$name] === 0) {
                // $pid = 0, this is the child thread

                /** @var ExchangeProviderInterface $provider */
                $this->processProvider(
                    $provider['class'], $name, isset($provider['expiration_interval']) ? $provider['expiration_interval'] : 5
                );
                exit;

            }
        }

        foreach ($pids as $childPid) {
            pcntl_waitpid($childPid, $status);
        }
    }

    private function processProvider($class, $name, $expirationInterval)
    {
        /** @var ExchangeProviderInterface $provider */
        $provider = new $class($name, $expirationInterval);
        $exchangeRate = $provider->getExchangeRate();

        \Ratchet\Client\connect('ws://127.0.0.1:8080')->then(
            function($conn) use($exchangeRate) {
                $conn->send($exchangeRate->toJson());
                $conn->close();
            },
            function($e) {
                echo "Could not connect: {$e->getMessage()}\n";
            }
        );

        // save all result in db for debug
        if ($this->db) {
            $this->db->saveExchangeRate($exchangeRate);
        }
    }
}