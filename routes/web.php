<?php

$routes = [
  '/' => 'landing.php',
];

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = str_replace('/lunar_store', '', $request_uri);

$page = $routes[$request_uri] ?? '404.php';

if (file_exists(BASE_PATH . '/views/' . $page)) {
  require BASE_PATH . '/views/' . $page;
} else {
  require BASE_PATH . '/views/404.php';
}
