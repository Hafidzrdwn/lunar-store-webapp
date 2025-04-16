<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Dashboard
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
test
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<?php if (isset($_SESSION['success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?= $_SESSION['success']; ?>',
      confirmButtonColor: '#435ebe'
    });
  </script>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php View::endSection(); ?>