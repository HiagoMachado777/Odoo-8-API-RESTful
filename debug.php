<?php

require_once ('models/ApiBanco.php');
//require_once ('models/Sales.php');

$api = new ApiBanco();
$api->enviarOdoo('ratao', 'jjjj@dfdf.c', '3', '3333333333', '12121', 'rua rua rua');
