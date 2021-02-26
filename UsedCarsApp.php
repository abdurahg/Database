<?php
require_once 'dbCredentials.php';
require_once 'UsedCarsModel.php';

$model = new UsedCarsModel();
print($model->createDealersDoc() . "\n");
print($model->createCountiesCitiesDoc() . "\n");

