<?php

$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->addPsr4('Unit\\', __DIR__ . '/unit/Mcustiel');
$loader->addPsr4('Mcustiel\\', __DIR__ . '/../src/Mcustiel');

define('FIXTURES_PATH', __DIR__ . '/fixtures');
