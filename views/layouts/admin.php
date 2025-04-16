<?php

if (!isset($_SESSION['admin_logged_in']) && !$_SESSION['admin_logged_in'] === true) {
  redirect('/admin/login');
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lunar Store Admin - <?php View::yield('title'); ?></title>
  <link rel="shortcut icon" href="<?= asset('client/images/logo.png'); ?>" type="image/x-icon">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="<?= asset('admin/compiled/css/app.css') ?>">
  <link rel="stylesheet" href="<?= asset('admin/compiled/css/app-dark.css') ?>">
  <link rel="stylesheet" href="<?= asset('admin/compiled/css/iconly.css') ?>">
  <link rel="stylesheet" href="<?= asset('admin/custom.css') ?>">


  <?php View::yield('custom_css'); ?>
</head>

<body>
  <script src="<?= asset('admin/static/js/initTheme.js') ?>"></script>

  <div id="app">
    <?= component('admin/sidebar'); ?>
    <div id="main" class="layout-navbar navbar-fixed">
      <?= component('admin/header'); ?>

      <div id="main-content">
        <?php View::yield('content'); ?>
      </div>

      <?= component('admin/footer'); ?>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src=" <?= asset('admin/static/js/components/dark.js') ?>">
  </script>
  <script src="<?= asset('admin/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>">
  </script>
  <script src="<?= asset('admin/compiled/js/app.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Include Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize the logout button
      $('.btnLogout').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, logout!',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = $(this).attr('href');
          }
        });
      });
    });
  </script>
  <?php View::yield('custom_js'); ?>
</body>

</html>