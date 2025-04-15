<?php
session_start();
// unset($_SESSION['isLogin']);
$_SESSION['isLogin'] = true;

$routes = [
  '/' => (is_auth() ? 'client/home.php' : 'landing.php'),
  '/aboutus' => 'aboutus.php',
  '/register' => 'client/auth/register.php',
  '/login' => 'client/auth/login.php',
];


// echo '<pre>';
// print_r($routes);
// echo '</pre>';
// die;


$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = str_replace('/lunar_store', '', $request_uri);

$page = $routes[$request_uri] ?? '404.php';
$page_file = BASE_PATH . '/views/' . $page;

if (!file_exists($page_file) || strpos(realpath($page_file), realpath(BASE_PATH . '/views/')) !== 0) {
  View::render('404.php');
  exit;
} else {
  View::render($page);
}
