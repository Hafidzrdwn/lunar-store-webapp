<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../config/validator.php';
require __DIR__ . '/../../functions.php';

// Handle Login
if (isset($_POST['login'])) {
  // Get form data
  $email = sanitizeInput($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  // Store old input data in session for form repopulation
  $_SESSION['old'] = [
    'email' => $email
  ];

  // Define validation rules
  $rules = [
    'email' => 'required|email',
    'password' => 'required|min:6'
  ];

  // Custom error messages
  $messages = [
    'login.required' => 'Email is required',
    'password.required' => 'Password is required',
    'password.min' => 'Password must be at least 6 characters'
  ];

  // Validate the input
  $validator = new Validator($_POST, $rules, $messages);

  if ($validator->fails()) {
    // Store validation errors in session
    $_SESSION['errors'] = $validator->errors();

    // Redirect back to login page
    redirect('/login');
    exit;
  }


  $user = getSingleData('users', 'email', $email);

  if ($user && password_verify($password, $user['password'])) {
    // Login successful
    // Set session data
    $_SESSION['isLogin'] = true;
    $_SESSION['user_data'] = [
      'id' => $user['id'],
      'email' => $user['email'],
      'username' => $user['username'],
      'name' => $user['name']
    ];

    // Clear old input and errors
    unset($_SESSION['old']);
    unset($_SESSION['errors']);

    // Set success message
    $_SESSION['success'] = 'Login successful! Welcome to Lunar Store.';

    // Redirect to dashboard or home page
    redirect('/');
    exit;
  } else {
    // Invalid credentials
    $_SESSION['errors'] = [
      'login' => ['Invalid email or password. Please try again.']
    ];

    // Redirect back to login page
    redirect('/login');
    exit;
  }
}

// Handle Registration
if (isset($_POST['register'])) {
  // Get form data
  $username = sanitizeInput($_POST['username'] ?? '');
  $fullname = sanitizeInput($_POST['fullname'] ?? '');
  $email = sanitizeInput($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';
  $terms = isset($_POST['terms']);

  // Store old input data in session for form repopulation
  $_SESSION['old'] = [
    'username' => $username,
    'fullname' => $fullname,
    'email' => $email
  ];

  // Define validation rules
  $rules = [
    'username' => 'required|min:4|unique:users,username',
    'fullname' => 'required|min:6',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6',
    'confirm_password' => 'required|min:6|confirmed:password',
    'terms' => 'required'
  ];

  // Custom error messages
  $messages = [
    'username.required' => 'Username is required',
    'username.min' => 'Username must be at least 4 characters',
    'fullname.required' => 'Fullname is required',
    'fullname.min' => 'Fullname must be at least 6 characters',
    'email.required' => 'Email address is required',
    'email.email' => 'Please enter a valid email address',
    'password.required' => 'Password is required',
    'password.min' => 'Password must be at least 6 characters',
    'confirm_password.required' => 'Please confirm your password',
    'confirm_password.min' => 'Confirm password must be at least 6 characters',
    'terms.required' => 'You must accept the Terms and Conditions'
  ];

  // Add custom validation for terms
  if (!$terms) {
    $_SESSION['errors']['terms'] = ['You must accept the Terms and Conditions'];
  }

  // Check if passwords match
  if ($password !== $confirm_password) {
    $_SESSION['errors']['confirm_password'] = ['Passwords do not match'];
  }

  // Validate the input
  $validator = new Validator($_POST, $rules, $messages);

  // Check if username already exists
  $existingUsername = getSingleData('users', 'username', $username);
  if ($existingUsername) {
    $_SESSION['errors']['username'] = ['This username is already taken'];
  }

  // Check if email already exists
  $existingEmail = getSingleData('users', 'email', $email);
  if ($existingEmail) {
    $_SESSION['errors']['email'] = ['This email address is already registered'];
  }

  if ($validator->fails() || isset($_SESSION['errors'])) {
    // Store validation errors in session
    $_SESSION['errors'] = $validator->errors();
    // Redirect back to registration page
    redirect('/register');
    exit;
  }

  // Create new user
  $userData = [
    'username' => $username,
    'name' => $fullname,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'created_at' => date('Y-m-d H:i:s'),
  ];

  $result = insertData('users', $userData);

  if ($result) {
    // Registration successful

    // Clear old input and errors
    unset($_SESSION['old']);
    unset($_SESSION['errors']);

    // Set success message
    $_SESSION['success'] = 'Registration successful! You can now log in.';

    // Redirect to login page
    redirect('/login');
    exit;
  } else {
    // Registration failed
    $_SESSION['errors'] = [
      'register' => ['Registration failed. Please try again.']
    ];

    // Redirect back to registration page
    redirect('/register');
    exit;
  }
}

// Logout functionality
if (isset($_GET['logout'])) {
  // Clear session
  unset($_SESSION['isLogin']);
  unset($_SESSION['user_data']);

  // Set success message
  $_SESSION['success'] = 'You have been successfully logged out.';

  // Redirect to login page
  redirect('/login');
  exit;
}
