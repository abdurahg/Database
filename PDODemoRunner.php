<?php
require_once 'dbCredentials.php';
require_once 'PDODemo.php';

$demo = new PDODemo();
print_r($demo->runSimpleQuery());
print_r($demo->runFetchAllQuery());
print($demo->runInsert('Ford', 'Fiesta'));
$no = print($demo->runAutoIncrementInsert('Test', 'Test', '1999', 9999, 'Test', 'Test', 999, 'Gjvk'));
$demo->runUpdate($no, 321123);
$demo->runDelete($no);
