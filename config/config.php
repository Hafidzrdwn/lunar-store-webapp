<?php

define('BASE_PATH', dirname(__DIR__));

// Load .env variables
if (file_exists(BASE_PATH . "/.env")) {
  $lines = file(BASE_PATH . "/.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue; // Ignore comments
    list($key, $value) = explode('=', $line, 2);
    putenv(trim($key) . '=' . trim($value));
  }
}

define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost:8080/lunar_store/');
define('PUBLIC_PATH', BASE_URL . 'public/');
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', APP_ENV === 'development');

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'lunar_store');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting
if (APP_DEBUG) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}
