<?php
require_once __DIR__ . '/config.php';

// Check if this is a direct request to this file
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
  // Handle AJAX requests directly
  handleAjaxRequests();
}

function handleAjaxRequests()
{
  // Get products data for DataTables
  if (isset($_POST['get_products'])) {
    getProductsDataTable();
    exit;
  }

  // Get categories for select box
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_categories'])) {
    getCategories();
    exit;
  }

  // Get a single product
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['productId'])) {
    getProduct($_GET['productId']);
    exit;
  }

  // Create a new product
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveProduct'])) {
    createProduct();
    exit;
  }

  // Update a product
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editProduct']) && isset($_POST['productId'])) {
    updateProduct($_POST['productId']);
    exit;
  }

  // Update a specific field
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateField']) && isset($_POST['id'])) {
    updateProductField($_POST['id']);
    exit;
  }

  // Delete products
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    deleteProducts();
    exit;
  }

  // If no valid request is found
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

// Get products data for DataTables
function getProductsDataTable()
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
    1 => 'p.id',
    3 => 'p.app_name',
    4 => 'c.title',
    5 => 'p.description',
    6 => 'p.notes',
    7 => 'p.has_type',
    8 => 'p.ready_stock'
  ];

  $orderBy = $columns[$orderColumn] ?? 'p.id';

  // Count total records
  $recordsTotal = countData('products');

  // Build query for filtered records
  $searchSql = '';
  if ($search !== '') {
    $searchSql = " WHERE p.app_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR c.title LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR p.description LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'
                  OR p.notes LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
  }

  // Count filtered records
  $sqlCount = "SELECT COUNT(*) as total FROM products p 
               LEFT JOIN product_categories c ON p.category_id = c.id" . $searchSql;
  $resultCount = mysqli_query($conn, $sqlCount);
  $rowCount = mysqli_fetch_assoc($resultCount);
  $recordsFiltered = $rowCount['total'];

  // Fetch data
  $sql = "SELECT p.id, p.app_name, p.description, p.notes, p.cover_img, 
          p.category_id, c.title as category_name, p.has_type, p.ready_stock 
          FROM products p 
          LEFT JOIN product_categories c ON p.category_id = c.id" . $searchSql .
    " ORDER BY " . $orderBy . " " . $orderDir .
    " LIMIT " . $start . ", " . $length;

  $data = query($sql);

  // Format data for DataTables
  $formattedData = [];
  foreach ($data as $row) {
    $formattedData[] = [
      'id' => $row['id'],
      'app_name' => $row['app_name'],
      'description' => $row['description'],
      'notes' => $row['notes'],
      'cover_img' => $row['cover_img'] ? public_path('/uploads/' . $row['cover_img']) : null,
      'category_id' => $row['category_id'],
      'category_name' => $row['category_name'],
      'has_type' => $row['has_type'],
      'ready_stock' => $row['ready_stock']
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

// Get categories for select box
function getCategories()
{
  $categories = query("SELECT id, title FROM product_categories ORDER BY title ASC");

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $categories]);
  exit;
}

// Get a single product
function getProduct($productId)
{
  $productId = sanitizeInput($productId);
  $product = getSingleData('products', 'id', $productId);

  if (!$product) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
  }

  // Add image URL if image exists
  $product['cover_img'] = ($product['cover_img']) ? public_path('/uploads/' . $product['cover_img']) : null;

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $product]);
  exit;
}

// Create a new product
function createProduct()
{
  // Get form data
  $app_name = sanitizeInput($_POST['app_name'] ?? '');
  $category_id = sanitizeInput($_POST['category_id'] ?? '');
  $description = sanitizeInput($_POST['description'] ?? '');
  $notes = sanitizeInput($_POST['notes'] ?? '');
  $has_type = isset($_POST['has_type']) ? intval($_POST['has_type']) : 0;
  $ready_stock = isset($_POST['ready_stock']) ? intval($_POST['ready_stock']) : 0;

  // Validate form data
  $validator = new Validator(
    [
      'app_name' => $app_name,
      'category_id' => $category_id,
      'description' => $description,
      'notes' => $notes,
      'has_type' => $has_type,
      'ready_stock' => $ready_stock
    ],
    [
      'app_name' => 'required|string|max:255',
      'category_id' => 'required|integer',
      'description' => 'string',
      'notes' => 'string',
      'has_type' => 'boolean',
      'ready_stock' => 'boolean'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Handle image upload
  $imageName = null;
  if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
    $imageName = uploadImage($_FILES['cover_img']);
    if (!$imageName) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
      exit;
    }
  }

  // Insert product
  $productData = [
    'app_name' => $app_name,
    'category_id' => $category_id,
    'description' => $description ?? '',
    'notes' => $notes ?? '',
    'cover_img' => $imageName ?? '',
    'has_type' => $has_type,
    'ready_stock' => $ready_stock
  ];

  $result = insertData('products', $productData);

  if ($result > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Product created successfully']);
    exit;
  } else {
    // Delete uploaded image if insert failed
    if ($imageName) {
      deleteUploadedImage($imageName);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to create product']);
    exit;
  }
}

// Update a product
function updateProduct($productId)
{
  $productId = sanitizeInput($productId);

  // Get form data
  $app_name = sanitizeInput($_POST['app_name'] ?? '');
  $category_id = sanitizeInput($_POST['category_id'] ?? '');
  $description = sanitizeInput($_POST['description'] ?? '');
  $notes = sanitizeInput($_POST['notes'] ?? '');
  $has_type = isset($_POST['has_type']) ? intval($_POST['has_type']) : 0;
  $ready_stock = isset($_POST['ready_stock']) ? intval($_POST['ready_stock']) : 0;

  // Check if product exists
  $product = getSingleData('products', 'id', $productId);

  if (!$product) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
  }

  // Validate form data
  $validator = new Validator(
    [
      'app_name' => $app_name,
      'category_id' => $category_id,
      'description' => $description,
      'notes' => $notes,
      'has_type' => $has_type,
      'ready_stock' => $ready_stock
    ],
    [
      'app_name' => 'required|string|max:255',
      'category_id' => 'required|integer',
      'description' => 'string',
      'notes' => 'string',
      'has_type' => 'boolean',
      'ready_stock' => 'boolean'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Handle image upload
  $imageName = $product['cover_img'];
  if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
    $newImageName = uploadImage($_FILES['cover_img']);
    if (!$newImageName) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
      exit;
    }

    // Delete old image if exists
    if ($imageName) {
      deleteUploadedImage($imageName);
    }

    $imageName = $newImageName;
  }

  // Update product
  $productData = [
    'app_name' => $app_name,
    'category_id' => $category_id,
    'description' => $description,
    'notes' => $notes,
    'has_type' => $has_type,
    'ready_stock' => $ready_stock
  ];

  // Only update image if a new one was uploaded
  if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
    $productData['cover_img'] = $imageName;
  }

  $result = updateData('products', $productData, 'id', $productId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    exit;
  } else {
    // Delete uploaded image if update failed
    if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
      deleteUploadedImage($imageName);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to update product']);
    exit;
  }
}

// Update a specific field of a product
function updateProductField($productId)
{
  // Get field and value
  $field = sanitizeInput($_POST['field'] ?? '');
  $value = sanitizeInput($_POST['value'] ?? '');
  $productId = sanitizeInput($productId);

  // Validate field
  $allowedFields = ['app_name', 'description', 'notes'];
  if (!in_array($field, $allowedFields)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
  }

  // Check if product exists
  $product = getSingleData('products', 'id', $productId);

  if (!$product) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
  }

  // Validate value based on field
  $validationRules = [
    'app_name' => 'required|string|max:255',
    'description' => 'string',
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
  $productData = [$field => $value];
  $result = updateData('products', $productData, 'id', $productId);

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

// Delete products
function deleteProducts()
{
  // Get product IDs
  $ids = $_POST['ids'] ?? [];

  if (empty($ids)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No products selected']);
    exit;
  }

  // Convert to array if string
  if (!is_array($ids)) {
    $ids = [$ids];
  }

  // Delete products
  $successCount = 0;

  foreach ($ids as $id) {
    $id = sanitizeInput($id);

    // Get product image before deleting
    $product = getSingleData('products', 'id', $id);

    $result = deleteData('products', 'id', $id);
    if ($result > 0) {
      $successCount++;

      // Delete image if exists
      if ($product && $product['cover_img']) {
        deleteUploadedImage($product['cover_img']);
      }
    }
  }

  header('Content-Type: application/json');
  echo json_encode([
    'success' => true,
    'message' => $successCount . ' product(s) deleted successfully'
  ]);
  exit;
}

// Helper function to upload image
function uploadImage($file)
{
  // Check if uploads directory exists, if not create it
  $uploadDir = BASE_PATH . '/public/uploads/products/';
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  // Validate file type
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  if (!in_array($file['type'], $allowedTypes)) {
    return false;
  }

  // Validate file size (max 2MB)
  if ($file['size'] > 2 * 1024 * 1024) {
    return false;
  }

  // Generate unique filename
  $filename = uniqid() . '_' . time() . '_' . sanitizeInput(basename($file['name']));
  $targetFile = $uploadDir . $filename;

  // Upload file
  if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    return 'products/' . $filename;
  }

  return false;
}

// Helper function to delete uploaded image
function deleteUploadedImage($filename)
{
  $filepath = BASE_PATH . '/public/uploads/' . $filename;
  if (file_exists($filepath)) {
    unlink($filepath);
    return true;
  }
  return false;
}
