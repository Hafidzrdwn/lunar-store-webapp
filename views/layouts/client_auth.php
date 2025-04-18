<?php
if (is_auth()) {
  redirect('/');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lunar Store - <?php View::yield('title'); ?></title>
  <link rel="shortcut icon" href="<?= asset('client/images/logo.png'); ?>" type="image/x-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&family=Righteous&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="<?= asset('client/css/style.css') ?>" />
  <link rel="stylesheet" href="<?= asset('client/css/custom.css') ?>" />

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <?php View::yield('custom_css'); ?>
</head>

<body class="min-h-screen bg-white">
  <!-- Main Content -->
  <?php View::yield('content'); ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php View::yield('custom_js'); ?>
</body>

</html>