<?php
/**
 * Created by PhpStorm.
 * User: bbs_m
 * Date: 6/5/2017
 * Time: 8:33 PM
 */
require dirname(__FILE__) . '/../vendor/autoload.php';

$conf = include dirname(__FILE__) . '/../conf.php';

//$connection = \app\Databases\SqliteDatabase::build($conf['db']);

$collector = new \app\Collectors\PcntlExchangeCollector($conf['providers']);
$collector->collectData();