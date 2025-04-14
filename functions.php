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
