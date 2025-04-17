<?php
require_once __DIR__ . '/config.php';

if (isset($_POST['get_users'])) {
  return getUsersDataTable();
}

if (isset($_POST['save_user'])) {
  return createUser();
}

if (isset($_POST['update_field'])) {
  $userId = $_POST['id'] ?? null;
  return updateUserField($userId);
}

if (isset($_GET['get_user'])) {
  $userId = $_GET['id'] ?? null;
  return getUser($userId);
}
if (isset($_POST['edit_user'])) {
  $userId = $_POST['id'] ?? null;
  return updateUser($userId);
}

if (isset($_POST['delete_user'])) {
  return deleteUsers();
}


// Get users data for DataTables
function getUsersDataTable()
{
  global $conn;

  // DataTables server-side parameters
  $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
  $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
  $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
  $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
  $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 1;
  $orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

  // Map DataTables column index to database column name
  $columns = [
    1 => 'id',
    2 => 'username',
    2 => 'name',
    3 => 'email',
    4 => 'phone',
    5 => 'address',
    6 => 'created_at'
  ];

  $orderBy = $columns[$orderColumn] ?? 'id';

  // Count total records
  $recordsTotal = countData('users');

  // Build query for filtered records
  $searchSql = '';
  if ($search !== '') {
    $searchSql = " WHERE username LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR email LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR phone LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
                  OR address LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
                  ";
  }

  // Count filtered records
  $sqlCount = "SELECT COUNT(*) as total FROM users" . $searchSql;
  $resultCount = mysqli_query($conn, $sqlCount);
  $rowCount = mysqli_fetch_assoc($resultCount);
  $recordsFiltered = $rowCount['total'];

  // Fetch data
  $sql = "SELECT id, username, name, email, phone, address, created_at FROM users" . $searchSql .
    " ORDER BY " . $orderBy . " " . $orderDir .
    " LIMIT " . $start . ", " . $length;

  $data = query($sql);

  // Format data for DataTables
  $formattedData = [];
  foreach ($data as $row) {
    $formattedData[] = [
      'id' => $row['id'],
      'username' => $row['username'],
      'name' => $row['name'],
      'email' => $row['email'],
      'phone' => $row['phone'],
      'address' => $row['address'],
      'created_at' => date('d M Y H:i', strtotime($row['created_at']))
    ];
  }

  // Return JSON response
  header('Content-Type: application/json');
  echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $formattedData
  ]);
  exit;
}

// Get a single user
function getUser($userId)
{
  $userId = sanitizeInput($userId);
  $user = getSingleData('users', 'id', $userId);

  if (!$user) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
  }

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $user]);
  exit;
}

// Create a new user
function createUser()
{
  // Get form data
  $username = sanitizeInput($_POST['username'] ?? '');
  $name = sanitizeInput($_POST['name'] ?? '');
  $email = sanitizeInput($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password_confirmation = $_POST['password_confirmation'] ?? '';
  $phone = sanitizeInput($_POST['phone'] ?? '');
  $address = sanitizeInput($_POST['address'] ?? '1');

  // Validate form data
  $validator = new Validator(
    [
      'username' => $username,
      'name' => $name,
      'email' => $email,
      'password' => $password,
      'password_confirmation' => $password_confirmation,
      'phone' => $phone,
      'address' => $address
    ],
    [
      'username' => 'required|min:4|unique:users,username',
      'name' => 'required|min:6',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:6',
      'password_confirmation' => 'required|confirmed:password',
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert user
  $userData = [
    'username' => $username,
    'name' => $name,
    'email' => $email,
    'password' => $hashedPassword,
    'phone' => $phone,
    'address' => $address,
    'created_at' => date('Y-m-d H:i:s')
  ];

  $result = insertData('users', $userData);

  if ($result > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'User created successfully']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to create user']);
    exit;
  }
}

// Update a user
function updateUser($userId)
{
  // Get form data
  $username = sanitizeInput($_POST['username'] ?? '');
  $name = sanitizeInput($_POST['name'] ?? '');
  $email = sanitizeInput($_POST['email'] ?? '');
  $phone = sanitizeInput($_POST['phone'] ?? '');
  $address = sanitizeInput($_POST['address'] ?? '');
  $userId = sanitizeInput($userId);

  // Check if user exists
  $user = getSingleData('users', 'id', $userId);

  if (!$user) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
  }

  // Validate form data
  $validationRules = [
    'username' => $user['username'] === $username ? 'required|min:4' : 'required|min:4|unique:users,username',
    'name' => 'required|min:6',
    'email' => $user['email'] === $email ? 'required|email' : 'required|email|unique:users,email',
  ];

  $validator = new Validator(
    [
      'username' => $username,
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'address' => $address
    ],
    $validationRules
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Update user
  $userData = [
    'username' => $username,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'address' => $address
  ];

  $result = updateData('users', $userData, 'id', $userId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    exit;
  }
}

// Update a specific field of a user
function updateUserField($userId)
{
  global $conn;

  // Get field and value
  $field = sanitizeInput($_POST['field'] ?? '');
  $value = sanitizeInput($_POST['value'] ?? '');
  $userId = sanitizeInput($userId);

  // Validate field
  $allowedFields = ['username', 'name', 'email', 'phone', 'address'];
  if (!in_array($field, $allowedFields)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
  }

  // Check if user exists
  $user = getSingleData('users', 'id', $userId);

  if (!$user) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
  }

  // Validate value based on field
  $validationRules = [
    'username' => 'required|min:4|unique:users,username',
    'name' => 'required|min:6',
    'email' => 'required|email|unique:users,email'
  ];

  $validator = new Validator(
    [$field => $value],
    [$field => $validationRules[$field]]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Update field
  $userData = [$field => $value];
  $result = updateData('users', $userData, 'id', $userId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Field updated successfully']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to update field']);
    exit;
  }
}

// Delete users
function deleteUsers()
{
  // Get user IDs
  $ids = $_POST['ids'] ?? [];

  if (empty($ids)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No users selected']);
    exit;
  }

  // Convert to array if string
  if (!is_array($ids)) {
    $ids = [$ids];
  }

  // Delete users
  $successCount = 0;

  foreach ($ids as $id) {
    $id = sanitizeInput($id);
    $result = deleteData('users', 'id', $id);
    if ($result > 0) {
      $successCount++;
    }
  }

  header('Content-Type: application/json');
  echo json_encode([
    'success' => true,
    'message' => $successCount . ' user(s) deleted successfully'
  ]);
  exit;
}
