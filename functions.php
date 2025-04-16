<?php

function site_url($path = '')
{
  return BASE_URL . ltrim($path, '/');
}

function asset($path = '')
{
  return PUBLIC_PATH . 'assets/' . ltrim($path, '/');
}

function public_path($path = '')
{
  return PUBLIC_PATH . ltrim($path, '/');
}

function redirect($path)
{
  $url = site_url($path);
  header("Location: $url", true);
  exit();
}

function component($component, $data = [])
{
  extract($data);
  include BASE_PATH . "/views/includes/{$component}.php";
}

function current_url()
{
  $url = str_replace('/lunar_store', '', $_SERVER['REQUEST_URI']);
  return parse_url($url, PHP_URL_PATH);
}

function is_auth()
{
  if (!isset($_SESSION['isLogin'])) return false;
  return $_SESSION['isLogin'] && isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true;
}

function toRupiah($number)
{
  return 'Rp' . number_format($number, 0, ',', '.');
}

function rupiahToNumber($number)
{
  return (int)str_replace(['Rp', '.', ' '], '', $number);
}

function time_elapsed_string($datetime, $full = false)
{
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  // Create units
  $units = [
    'y' => 'year',
    'm' => 'month',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  ];

  foreach ($units as $key => &$text) {
    if ($diff->$key) {
      $text = $diff->$key . ' ' . $text . ($diff->$key > 1 ? 's' : '');
    } else {
      unset($units[$key]);
    }
  }

  if (!$full) $units = array_slice($units, 0, 1); // just the first unit

  return $units
    ? implode(', ', $units) . ' ago'
    : 'just now';
}
