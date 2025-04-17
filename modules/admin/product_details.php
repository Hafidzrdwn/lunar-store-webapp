<?php
require_once __DIR__ . '/config.php';

// Check if this is a direct request to this file
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
  // Handle AJAX requests directly
  handleAjaxRequests();
}

function handleAjaxRequests()
{
  // Get product details data for DataTables
  if (isset($_POST['get_product_details'])) {
    getProductDetailsDataTable();
    exit;
  }

  // Get products for select box
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_products'])) {
    getProducts();
    exit;
  }

  // Get product types for select box
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_product_types'])) {
    getProductTypes();
    exit;
  }

  // Get a single product detail
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['detailId'])) {
    getProductDetail($_GET['detailId']);
    exit;
  }

  // Create a new product detail
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveDetail'])) {
    createProductDetail();
    exit;
  }

  // Update a product detail
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editDetail']) && isset($_POST['detailId'])) {
    updateProductDetail($_POST['detailId']);
    exit;
  }

  // Update a specific field
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateField']) && isset($_POST['id'])) {
    updateProductDetailField($_POST['id']);
    exit;
  }

  // Delete product details
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_detail'])) {
    deleteProductDetails();
    exit;
  }

  // If no valid request is found
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

// Get product details data for DataTables
function getProductDetailsDataTable()
{
  global $conn;

  // DataTables server-side parameters
  $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
  $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
  $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
  $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
  $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 2; // Default sort by product name
  $orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

  // Map DataTables column index to database column name
  $columns = [
    1 => 'pd.id',
    2 => 'p.app_name',
    3 => 'pt.type_name',
    4 => 'pd.duration',
    5 => 'pd.price',
    6 => 'pd.notes'
  ];

  $orderBy = $columns[$orderColumn] ?? 'p.app_name, pt.type_name';

  // Count total records
  $recordsTotal = countData('product_details');

  // Build query for filtered records
  $searchSql = '';
  if ($search !== '') {
    $searchSql = " WHERE p.app_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR pt.type_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR pd.duration LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
                  OR pd.price LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
                  OR pd.notes LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
  }

  // Count filtered records
  $sqlCount = "SELECT COUNT(*) as total FROM product_details pd 
               LEFT JOIN products p ON pd.product_id = p.id
               LEFT JOIN product_types pt ON pd.product_type_id = pt.id" . $searchSql;
  $resultCount = mysqli_query($conn, $sqlCount);
  $rowCount = mysqli_fetch_assoc($resultCount);
  $recordsFiltered = $rowCount['total'];

  // Fetch data - always order by product name and type name first for grouping
  $sql = "SELECT pd.id, pd.product_id, pd.product_type_id, pd.duration, 
          pd.price, pd.notes, p.app_name as product_name, pt.type_name as type_name 
          FROM product_details pd 
          LEFT JOIN products p ON pd.product_id = p.id
          LEFT JOIN product_types pt ON pd.product_type_id = pt.id" . $searchSql .
    " ORDER BY p.app_name ASC, pt.type_name ASC, " . $orderBy . " " . $orderDir .
    " LIMIT " . $start . ", " . $length;

  $result = mysqli_query($conn, $sql);
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  // Format data for DataTables with grouping information
  $formattedData = [];
  $lastProduct = null;
  $lastProductType = null;

  foreach ($data as $row) {
    $productName = $row['product_name'];
    $typeName = $row['type_name'];

    // Format price with currency
    $formattedPrice = toRupiah($row['price']);

    $rowData = [
      'id' => $row['id'],
      'product_id' => $row['product_id'],
      'product_name' => $productName,
      'product_type_id' => $row['product_type_id'],
      'type_name' => $typeName,
      'duration' => $row['duration'],
      'price' => $row['price'],
      'price_formatted' => $formattedPrice,
      'notes' => $row['notes'],
      'show_product' => ($lastProduct !== $productName),
      'show_type' => ($lastProduct !== $productName || $lastProductType !== $typeName)
    ];

    $formattedData[] = $rowData;

    $lastProduct = $productName;
    $lastProductType = $typeName;
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

// Get products for select box
function getProducts()
{
  $products = query("SELECT id, app_name FROM products ORDER BY app_name ASC");

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $products]);
  exit;
}

// Get product types for select box
function getProductTypes()
{
  $productTypes = query("SELECT id, type_name FROM product_types ORDER BY type_name ASC");

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $productTypes]);
  exit;
}

// Get a single product detail
function getProductDetail($detailId)
{
  $detailId = sanitizeInput($detailId);

  $sql = "SELECT pd.*, p.app_name as product_name, pt.type_name as type_name 
          FROM product_details pd
          LEFT JOIN products p ON pd.product_id = p.id
          LEFT JOIN product_types pt ON pd.product_type_id = pt.id
          WHERE pd.id = '$detailId'";

  $result = query($sql);
  $detail = !empty($result) ? $result[0] : null;

  if (!$detail) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product detail not found']);
    exit;
  }

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $detail]);
  exit;
}

// Create a new product detail
function createProductDetail()
{
  global $conn;

  // Get form data
  $product_id = sanitizeInput($_POST['product_id'] ?? '');
  $product_type_id = sanitizeInput($_POST['product_type_id'] ?? '');
  $durations = $_POST['duration'] ?? [];
  $duration_units = $_POST['duration_unit'] ?? [];
  $prices = $_POST['price'] ?? [];
  $notes = sanitizeInput($_POST['notes'] ?? '');

  // Validate main form data
  $validator = new Validator(
    [
      'product_id' => $product_id,
      'product_type_id' => $product_type_id,
      'notes' => $notes
    ],
    [
      'product_id' => 'required|integer',
      'product_type_id' => 'required|integer',
      'notes' => 'string'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Start transaction
  mysqli_begin_transaction($conn);
  $success = true;
  $insertCount = 0;

  // Process each price entry
  for ($i = 0; $i < count($prices); $i++) {
    if (empty($prices[$i])) continue; // Skip empty price entries

    $duration = isset($durations[$i]) ? sanitizeInput($durations[$i]) : null;
    $duration_unit = isset($duration_units[$i]) ? sanitizeInput($duration_units[$i]) : null;
    $price = sanitizeInput($prices[$i]);

    // Validate price entry
    $entryValidator = new Validator(
      [
        'price' => $price,
        'duration' => $duration,
        'duration_unit' => $duration_unit
      ],
      [
        'price' => 'required|numeric',
        'duration' => 'numeric',
        'duration_unit' => 'string'
      ]
    );

    if ($entryValidator->fails()) {
      $success = false;
      break;
    }

    // Insert product detail
    $detailData = [
      'product_id' => $product_id,
      'product_type_id' => $product_type_id,
      'duration' => $duration,
      'duration_unit' => $duration_unit,
      'price' => $price,
      'notes' => $notes
    ];

    $result = insertData('product_details', $detailData);

    if ($result > 0) {
      $insertCount++;
    } else {
      $success = false;
      break;
    }
  }

  // Commit or rollback transaction
  if ($success && $insertCount > 0) {
    mysqli_commit($conn);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => $insertCount . ' product detail(s) created successfully']);
    exit;
  } else {
    mysqli_rollback($conn);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to create product detail']);
    exit;
  }
}

// Update a product detail
function updateProductDetail($detailId)
{
  global $conn;

  $detailId = sanitizeInput($detailId);

  // Get form data
  $product_id = sanitizeInput($_POST['product_id'] ?? '');
  $product_type_id = sanitizeInput($_POST['product_type_id'] ?? '');
  $duration = sanitizeInput($_POST['duration'] ?? '');
  $duration_unit = sanitizeInput($_POST['duration_unit'] ?? '');
  $price = sanitizeInput($_POST['price'] ?? '');
  $notes = sanitizeInput($_POST['notes'] ?? '');

  // Check if product detail exists
  $detail = getSingleData('product_details', 'id', $detailId);

  if (!$detail) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product detail not found']);
    exit;
  }

  // Validate form data
  $validator = new Validator(
    [
      'product_id' => $product_id,
      'product_type_id' => $product_type_id,
      'duration' => $duration,
      'duration_unit' => $duration_unit,
      'price' => $price,
      'notes' => $notes
    ],
    [
      'product_id' => 'required|integer',
      'product_type_id' => 'required|integer',
      'duration' => 'numeric',
      'duration_unit' => 'string',
      'price' => 'required|numeric',
      'notes' => 'string'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Update product detail
  $detailData = [
    'product_id' => $product_id,
    'product_type_id' => $product_type_id,
    'duration' => $duration,
    'duration_unit' => $duration_unit,
    'price' => $price,
    'notes' => $notes
  ];

  $result = updateData('product_details', $detailData, 'id', $detailId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Product detail updated successfully']);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to update product detail']);
    exit;
  }
}

// Update a specific field of a product detail
function updateProductDetailField($detailId)
{
  global $conn;

  // Get field and value
  $field = sanitizeInput($_POST['field'] ?? '');
  $value = sanitizeInput($_POST['value'] ?? '');
  $detailId = sanitizeInput($detailId);

  // Validate field
  $allowedFields = ['product_id', 'product_type_id', 'duration', 'duration_unit', 'price', 'notes'];
  if (!in_array($field, $allowedFields)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
  }

  // Check if product detail exists
  $detail = getSingleData('product_details', 'id', $detailId);

  if (!$detail) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product detail not found']);
    exit;
  }

  // Validate value based on field
  $validationRules = [
    'product_id' => 'required|integer',
    'product_type_id' => 'required|integer',
    'duration' => 'numeric',
    'duration_unit' => 'string',
    'price' => 'required|numeric',
    'notes' => 'string'
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
  $detailData = [$field => $value];
  $result = updateData('product_details', $detailData, 'id', $detailId);

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

// Delete product details
function deleteProductDetails()
{
  global $conn;

  // Get product detail IDs
  $ids = $_POST['ids'] ?? [];

  if (empty($ids)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No product details selected']);
    exit;
  }

  // Convert to array if string
  if (!is_array($ids)) {
    $ids = [$ids];
  }

  // Delete product details
  $successCount = 0;

  foreach ($ids as $id) {
    $id = sanitizeInput($id);
    $result = deleteData('product_details', 'id', $id);
    if ($result > 0) {
      $successCount++;
    }
  }

  header('Content-Type: application/json');
  echo json_encode([
    'success' => true,
    'message' => $successCount . ' product detail(s) deleted successfully'
  ]);
  exit;
}
