<?php

require 'vendor/autoload.php';

$token = "";
$key = "";

$OrderModule = new \TomsSweip\OrderModule($token, $key);
$OrderModule->setCodes(["QGAMEX18041600000033"]);
$response = $OrderModule->getCargoTrack();

var_dump($response);