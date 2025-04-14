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
