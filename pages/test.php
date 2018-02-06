<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../bootstrap.php';

$request = Request::createFromGlobals();
$response = new \App\Classes\Response(__DIR__ . '/../views');
$controller = new \App\Controllers\Test($request, $response);

$response = $controller->__invoke();
$response->send();