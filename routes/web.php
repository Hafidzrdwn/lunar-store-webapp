<?php
session_start();

$routes = [
  // CLIENT
  '/' => (is_auth() ? 'client/home.php' : 'landing.php'),
  '/aboutus' => 'aboutus.php',
  '/register' => 'client/auth/register.php',
  '/login' => 'client/auth/login.php',
  '/catalog' => 'client/catalog.php',
  '/cart' => 'client/cart.php',
  '/orders' => 'client/orders.php',
  '/details' => 'client/details.php',



  // ADMIN
  '/admin' => 'admin/dashboard.php',
  '/admin/login' => 'admin/login.php',

  // MODULES
  // MASTER DATA
  '/admin/users' => 'admin/users.php',
  '/admin/product_categories' => 'admin/product/categories.php',
  '/admin/products' => 'admin/product/products.php',
  '/admin/product_types' => 'admin/product/types.php',
  '/admin/product_details' => 'admin/product/details.php',
];


// echo '<pre>';
// print_r($routes);
// echo '</pre>';
// die;


$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$folder_name = basename(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$request_uri = str_replace($folder_name, '', $request_uri);
$request_uri = ($request_uri == "/") ? $request_uri : rtrim($request_uri, '/');


$page = $routes[$request_uri] ?? '404.php';
$page_file = BASE_PATH . '/views/' . $page;

if (!file_exists($page_file) || strpos(realpath($page_file), realpath(BASE_PATH . '/views/')) !== 0) {
  View::render('404.php');
  exit;
} else {
  View::render($page);
}
