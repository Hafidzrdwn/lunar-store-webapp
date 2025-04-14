<?php

$host = DB_HOST;
$db = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;

try {
  $conn = mysqli_connect($host, $user, $pass, $db);
} catch (Exception $e) {
  die("DB Connection failed: " . $e->getMessage());
}
