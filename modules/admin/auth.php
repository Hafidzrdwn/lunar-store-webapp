<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../config/validator.php';
require __DIR__ . '/../../functions.php';

// Check if the form is submitted
if (isset($_POST['login'])) {
  // Get form data
  $username = sanitizeInput($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $remember = isset($_POST['remember']);

  $_SESSION['old'] = [
    'username' => $username
  ];

  $rules = [
    'username' => 'required',
    'password' => 'required'
  ];

  $messages = [
    'username.required' => 'Username is required.',
    'password.required' => 'Password is required.',
  ];

  // Validate the input
  $validator = new Validator($_POST, $rules, $messages);

  if ($validator->fails()) {
    $_SESSION['errors'] = $validator->errors();

    redirect('/admin/login');
    exit;
  }

  // Check credentials against database
  $user = getSingleData('user_admin', 'username', $username);

  if ($user && password_verify($password, $user['password'])) {
    // Login successful

    // Set session data
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_fullname'] = $user['fullname'];

    // Handle remember me functionality
    if ($remember) {
      // Generate a secure token
      $token = bin2hex(random_bytes(32));
      $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 days

      // Store token in database
      $tokenData = [
        'user_id' => $user['id'],
        'token' => password_hash($token, PASSWORD_DEFAULT),
        'expires_at' => $expires,
        'created_at' => date('Y-m-d H:i:s')
      ];

      insertData('admin_remember_tokens', $tokenData);

      // Set cookie
      setcookie('admin_remember', $user['id'] . ':' . $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
    }

    // Clear old input and errors
    unset($_SESSION['old']);
    unset($_SESSION['errors']);

    // Set success message
    $_SESSION['success'] = 'Login successful! Welcome to Lunar Store Admin Dashboard.';

    // Redirect to admin dashboard
    redirect('/admin');
    exit;
  } else {
    // Invalid credentials
    $_SESSION['errors'] = [
      'login' => ['Invalid username or password. Please try again.']
    ];

    // Redirect back to login page
    redirect('/admin/login');
    exit;
  }
}

// Logout functionality
if (isset($_GET['logout'])) {
  // Check if user is logged in
  if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    redirect('/admin/login');
    exit;
  }

  // check if user has remember me token and delete it
  if (isset($_COOKIE['admin_remember'])) {
    list($user_id, $token) = explode(':', $_COOKIE['admin_remember']);

    // Delete the token from the database
    $query = "DELETE FROM admin_remember_tokens WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
  }

  // Clear session
  unset($_SESSION['admin_logged_in']);
  unset($_SESSION['admin_id']);
  unset($_SESSION['admin_username']);
  unset($_SESSION['admin_fullname']);

  // Clear remember me cookie
  setcookie('admin_remember', '', time() - 3600, '/', '', false, true);

  // Set success message
  $_SESSION['success'] = 'You have been successfully logged out.';

  // Redirect to login page
  redirect('/admin/login');
  exit;
}
