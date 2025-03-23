<?php

$conn = mysqli_connect('localhost', 'root', 'admin', 'lunar_store');

if (!$conn) {
  die("Database connection failed");
}
