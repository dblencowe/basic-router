<?php

require_once __DIR__ . '/../bootstrap.php';

$pagesDirectory = __DIR__ . '/../pages';
$router = new \App\Classes\Router($pagesDirectory);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = $router->match($path);

require $route;