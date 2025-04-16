<?php View::extend('admin_auth'); ?>
<?php View::startSection('title'); ?>
Login
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<div class="auth-logo d-flex align-items-center gap-3">
  <img src="<?= asset('client/images/logo.png'); ?>" alt="Logo">
  <h5>LUNAR STORE</h5>
</div>
<h1 class="auth-title">Log in.</h1>
<p class="auth-subtitle mb-3">Log in to Lunar Store Admin Dashboard Panel.</p>

<?php if (isset($_SESSION['errors']['login'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['errors']['login'][0]; ?>
  </div>
<?php endif; ?>

<form action="<?= site_url('/modules/admin/auth.php'); ?>" method="POST">
  <div class="form-group position-relative has-icon-left mb-4">
    <input type="text" class="form-control form-control-xl <?= isset($_SESSION['errors']['username']) ? 'is-invalid' : ''; ?>"
      name="username" placeholder="Username" value="<?= $_SESSION['old']['username'] ?? ''; ?>">
    <div class="form-control-icon">
      <i class="bi bi-person"></i>
    </div>
    <?php if (isset($_SESSION['errors']['username'])): ?>
      <div class="invalid-feedback">
        <?= $_SESSION['errors']['username'][0]; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="form-group position-relative has-icon-left mb-4">
    <input type="password" class="form-control form-control-xl <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : ''; ?>"
      name="password" placeholder="Password">
    <div class="form-control-icon">
      <i class="bi bi-shield-lock"></i>
    </div>
    <?php if (isset($_SESSION['errors']['password'])): ?>
      <div class="invalid-feedback">
        <?= $_SESSION['errors']['password'][0]; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="form-check form-check-lg d-flex align-items-end">
    <input class="form-check-input me-2 c-pointer" name="remember" type="checkbox" id="flexCheckDefault">
    <label class="form-check-label text-gray-600 c-pointer" for="flexCheckDefault">
      Keep me logged in
    </label>
  </div>
  <button type="submit" name="login" class="btn btn-primary btn-block btn-lg shadow-lg mt-4 fs-5">
    Log in <i class="fas fa-sign-in-alt ms-1"></i>
  </button>
</form>

<?php
unset($_SESSION['old']);
View::endSection(); ?>

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