<?php
require_once __DIR__ . '/config.php';

// Check if this is a direct request to this file
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
  // Handle AJAX requests directly
  handleAjaxRequests();
}

function handleAjaxRequests()
{
  // Get types data for DataTables
  if (isset($_POST['get_types'])) {
    getTypesDataTable();
    exit;
  }

  // Get app name for select box
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_app_name'])) {
    getAppName();
    exit;
  }

  // Get a single type
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['typeId'])) {
    getProductType($_GET['typeId']);
    exit;
  }

  // Create a new type
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveType'])) {
    createType();
    exit;
  }

  // Update a type
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editType']) && isset($_POST['typeId'])) {
    updateType($_POST['typeId']);
    exit;
  }

  // Update a specific field
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateField']) && isset($_POST['id'])) {
    updateTypeField($_POST['id']);
    exit;
  }

  // Delete types
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_type'])) {
    deleteTypes();
    exit;
  }

  // If no valid request is found
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

// Get types data for DataTables
function getTypesDataTable()
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
    2 => 'app_name',
    3 => 'type_name',
    4 => 'description'
  ];

  $orderBy = $columns[$orderColumn] ?? 'id';

  // Count total records
  $recordsTotal = countData('product_types');

  // Build query for filtered records
  $searchSql = '';
  if ($search !== '') {
    $searchSql = " WHERE app_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR type_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
                  OR description LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
  }

  // Count filtered records
  $sqlCount = "SELECT COUNT(*) as total FROM product_types" . $searchSql;
  $resultCount = mysqli_query($conn, $sqlCount);
  $rowCount = mysqli_fetch_assoc($resultCount);
  $recordsFiltered = $rowCount['total'];

  // Fetch data
  $sql = "SELECT id, app_name, type_name, description FROM product_types" . $searchSql .
    " ORDER BY " . $orderBy . " " . $orderDir .
    " LIMIT " . $start . ", " . $length;

  $data = query($sql);

  // Format data for DataTables
  $formattedData = [];
  foreach ($data as $row) {
    $formattedData[] = [
      'id' => $row['id'],
      'app_name' => $row['app_name'],
      'type_name' => $row['type_name'],
      'description' => $row['description']
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

// Get app name for select box
function getAppName()
{
  $products = query("SELECT app_name FROM products WHERE has_type = 1 ORDER BY app_name ASC");

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $products]);
  exit;
}

// Get a single type
function getProductType($typeId)
{
  $typeId = sanitizeInput($typeId);
  $type = getSingleData('product_types', 'id', $typeId);

  if (!$type) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Type not found']);
    exit;
  }

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $type]);
  exit;
}

// Create a new type
function createType()
{
  // Get form data
  $app_name = sanitizeInput($_POST['app_name'] ?? '');
  $type_name = sanitizeInput($_POST['type_name'] ?? '');
  $description = sanitizeInput($_POST['description'] ?? '');

  // Validate form data
  $validator = new Validator(
    [
      'app_name' => $app_name,
      'type_name' => $type_name,
      'description' => $description
    ],
    [
      'app_name' => 'required',
      'type_name' => 'required|string|max:255',
      'description' => 'string'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Check if type name already exists
  $existingType = getSingleData('product_types', 'type_name', $type_name);
  if ($existingType) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => ['type_name' => ['Nama tipe sudah digunakan']]]);
    exit;
  }

  // Insert type
  $typeData = [
    'app_name' => $app_name,
    'type_name' => $type_name,
    'description' => $description
  ];

  $result = insertData('product_types', $typeData);

  if ($result > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Tipe produk berhasil dibuat']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Gagal membuat tipe produk']);
    exit;
  }
}

// Update a type
function updateType($typeId)
{
  $typeId = sanitizeInput($typeId);

  // Get form data
  $app_name = sanitizeInput($_POST['app_name'] ?? '');
  $type_name = sanitizeInput($_POST['type_name'] ?? '');
  $description = sanitizeInput($_POST['description'] ?? '');

  // Check if type exists
  $type = getSingleData('product_types', 'id', $typeId);

  if (!$type) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tipe produk tidak ditemukan']);
    exit;
  }

  // Validate form data
  $validator = new Validator(
    [
      'app_name' => $app_name,
      'type_name' => $type_name,
      'description' => $description
    ],
    [
      'app_name' => 'required',
      'type_name' => 'required|string|max:255',
      'description' => 'string'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Check if type name already exists (excluding current type)
  $existingType = getSingleData('product_types', 'type_name', $type_name);
  if ($existingType && $existingType['id'] != $typeId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => ['type_name' => ['Nama tipe sudah digunakan']]]);
    exit;
  }

  // Update type
  $typeData = [
    'app_name' => $app_name,
    'type_name' => $type_name,
    'description' => $description
  ];

  $result = updateData('product_types', $typeData, 'id', $typeId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Tipe produk berhasil diperbarui']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui tipe produk']);
    exit;
  }
}

// Update a specific field of a type
function updateTypeField($typeId)
{
  // Get field and value
  $field = sanitizeInput($_POST['field'] ?? '');
  $value = sanitizeInput($_POST['value'] ?? '');
  $typeId = sanitizeInput($typeId);

  // Validate field
  $allowedFields = ['app_name', 'type_name', 'description'];
  if (!in_array($field, $allowedFields)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Field tidak valid']);
    exit;
  }

  // Check if type exists
  $type = getSingleData('product_types', 'id', $typeId);

  if (!$type) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tipe produk tidak ditemukan']);
    exit;
  }

  // Validate value based on field
  $validationRules = [
    'app_name' => 'required',
    'type_name' => 'required|string|max:255',
    'description' => 'string'
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

  // If updating type_name, check for uniqueness
  if ($field === 'type_name' && $value !== $type['type_name']) {
    $existingType = getSingleData('product_types', 'type_name', $value);
    if ($existingType && $existingType['id'] != $typeId) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Nama tipe sudah digunakan']);
      exit;
    }
  }

  // Update field
  $typeData = [$field => $value];
  $result = updateData('product_types', $typeData, 'id', $typeId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Field berhasil diperbarui']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui field']);
    exit;
  }
}

// Delete types
function deleteTypes()
{
  global $conn;

  // Get type IDs
  $ids = $_POST['ids'] ?? [];

  if (empty($ids)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tidak ada tipe yang dipilih']);
    exit;
  }

  // Convert to array if string
  if (!is_array($ids)) {
    $ids = [$ids];
  }

  // Check if types are used in products
  foreach ($ids as $id) {
    $id = sanitizeInput($id);
    $sql = "SELECT COUNT(*) as count FROM product_details WHERE product_type_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);


    if ($row['count'] > 0) {
      $query_products = "SELECT p.app_name FROM product_details pd
                         JOIN products p ON pd.product_id = p.id
                         WHERE pd.product_type_id = '$id'";
      $result_products = query($query_products);
      $product_names = array_map(function ($product) {
        return $product['app_name'];
      }, $result_products);
      $product_names = array_unique($product_names);
    }
  }

  if (!empty($product_names)) {
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'message' => 'Tipe berikut sedang digunakan oleh produk: ' . implode(', ', $product_names)
    ]);
    exit;
  }

  // Delete types
  $successCount = 0;

  foreach ($ids as $id) {
    $id = sanitizeInput($id);
    $result = deleteData('product_types', 'id', $id);
    if ($result > 0) {
      $successCount++;
    }
  }

  header('Content-Type: application/json');
  echo json_encode([
    'success' => true,
    'message' => $successCount . ' tipe produk berhasil dihapus'
  ]);
  exit;
}
