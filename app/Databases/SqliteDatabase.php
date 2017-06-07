<?php

namespace app\Databases;
use app\Models\ExchangeRateModel;
use Doctrine\DBAL\Connection;

class SqliteDatabase implements DatabaseInterface
{
    /**
     * @var Connection
     */
    private $connection;

    private function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function connect()
    {
        $this->connection->connect();
    }

    /**
     * @param $params
     * @return $this
     */
    public static function build($params)
    {
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'path' => $params['path'],
            'driver' => 'pdo_sqlite'
        );
        $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $connection->executeQuery(self::getSchemaSql());

        return new self($connection);
    }

    public static function getSchemaSql()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS exchange_rate (
            provider_name  VARCHAR PRIMARY KEY,
            type  VARCHAR,
            `value` FLOAT  NOT NULL,
            `time`  TIMESTAMP NOT NULL
        )';

        return $sql;
    }

    public function saveExchangeRate(ExchangeRateModel $rateModel)
    {
        $this->connection->executeQuery(
            'REPLACE INTO exchange_rate (provider_name, type, `value`, `time`) VALUES (?,?,?,?)',
            [$rateModel->getProviderName(), $rateModel->getType(), $rateModel->getValue(), $rateModel->getTime()]
        );
    }
}