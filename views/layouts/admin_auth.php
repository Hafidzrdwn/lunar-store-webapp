<?php
// Check for remember me cookie
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['admin_remember'])) {
  list($user_id, $token) = explode(':', $_COOKIE['admin_remember']);

  // Find the token in the database
  global $conn;
  $query = "SELECT * FROM admin_remember_tokens 
              WHERE user_id = ? 
              AND expires_at > NOW() 
              ORDER BY created_at DESC LIMIT 1";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'i', $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $remember = mysqli_fetch_assoc($result);

  if ($remember && password_verify($token, $remember['token'])) {
    // Token is valid, get the user
    $user = getSingleData('user_admin', 'id', $user_id);

    if ($user) {
      // Auto login the user
      $_SESSION['admin_logged_in'] = true;
      $_SESSION['admin_id'] = $user['id'];
      $_SESSION['admin_username'] = $user['username'];
      $_SESSION['admin_fullname'] = $user['fullname'];

      // Redirect to admin dashboard
      redirect('/admin');
      exit;
    }
  }

  // Invalid remember token, clear the cookie
  setcookie('admin_remember', '', time() - 3600, '/', '', false, true);
} else if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
  // User is already logged in, redirect to admin dashboard
  redirect('/admin');
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
  <link rel="stylesheet" href="<?= asset('admin/compiled/css/auth.css') ?>">
  <link rel="stylesheet" href="<?= asset('admin/custom.css') ?>">

  <?php View::yield('custom_css'); ?>
</head>

<body>
  <script src="<?= asset('admin/static/js/initTheme.js') ?>"></script>
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <?php View::yield('content'); ?>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php View::yield('custom_js'); ?>
</body>

</html>