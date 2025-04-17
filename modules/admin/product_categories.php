<?php
require_once __DIR__ . '/config.php';

// Check if this is a direct request to this file
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
  // Handle AJAX requests directly
  handleAjaxRequests();
}

function handleAjaxRequests()
{
  // Get categories data for DataTables
  if (isset($_POST['get_categories'])) {
    getCategoriesDataTable();
    exit;
  }

  // Get a single category
  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['categoryId'])) {
    getCategory($_GET['categoryId']);
    exit;
  }

  // Create a new category
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveCategory'])) {
    createCategory();
    exit;
  }

  // Update a category
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editCategory']) && isset($_POST['categoryId'])) {
    updateCategory($_POST['categoryId']);
    exit;
  }

  // Update a specific field
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateField']) && isset($_POST['id'])) {
    updateCategoryField($_POST['id']);
    exit;
  }

  // Delete categories
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    deleteCategories();
    exit;
  }

  // If no valid request is found
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

// Get categories data for DataTables
function getCategoriesDataTable()
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
    3 => 'title',
    4 => 'slug',
    5 => 'description'
  ];

  $orderBy = $columns[$orderColumn] ?? 'id';

  // Count total records
  $recordsTotal = countData('product_categories');

  // Build query for filtered records
  $searchSql = '';
  if ($search !== '') {
    $searchSql = " WHERE title LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR slug LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' 
                  OR description LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
  }

  // Count filtered records
  $sqlCount = "SELECT COUNT(*) as total FROM product_categories" . $searchSql;
  $resultCount = mysqli_query($conn, $sqlCount);
  $rowCount = mysqli_fetch_assoc($resultCount);
  $recordsFiltered = $rowCount['total'];

  // Fetch data
  $sql = "SELECT id, title, slug, description, image FROM product_categories" . $searchSql .
    " ORDER BY " . $orderBy . " " . $orderDir .
    " LIMIT " . $start . ", " . $length;

  $data = query($sql);

  // Format data for DataTables
  $formattedData = [];
  foreach ($data as $row) {
    $formattedData[] = [
      'id' => $row['id'],
      'title' => $row['title'],
      'slug' => $row['slug'],
      'description' => $row['description'],
      'image' => $row['image'] ? public_path('/uploads/' . $row['image']) : null
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

// Get a single category
function getCategory($categoryId)
{
  $categoryId = sanitizeInput($categoryId);
  $category = getSingleData('product_categories', 'id', $categoryId);

  if (!$category) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Category not found']);
    exit;
  }

  // Add image URL if image exists
  $category['image'] = ($category['image']) ? public_path('/uploads/' . $category['image']) : null;

  header('Content-Type: application/json');
  echo json_encode(['success' => true, 'data' => $category]);
  exit;
}

// Create a new category
function createCategory()
{
  // Get form data
  $title = sanitizeInput($_POST['title'] ?? '');
  $slug = sanitizeInput($_POST['slug'] ?? '');
  $description = sanitizeInput($_POST['description'] ?? '');

  // Validate form data
  $validator = new Validator(
    [
      'title' => $title,
      'slug' => $slug,
      'description' => $description
    ],
    [
      'title' => 'required|string|max:255',
      'slug' => 'required|string|max:255',
      'description' => 'string'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Handle image upload
  $imageName = null;
  if (isset($_FILES['image']) && $_FILES['image'] && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageName = uploadImage($_FILES['image']);
    if (!$imageName) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
      exit;
    }
  }

  // Insert category
  $categoryData = [
    'title' => $title,
    'slug' => $slug,
    'description' => $description ?? '',
    'image' => $imageName ?? ''
  ];


  $result = insertData('product_categories', $categoryData);

  if ($result > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Category created successfully']);
    exit;
  } else {
    // Delete uploaded image if insert failed
    if ($imageName) {
      deleteUploadedImage($imageName);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to create category']);
    exit;
  }
}

// Update a category
function updateCategory($categoryId)
{
  $categoryId = sanitizeInput($categoryId);

  // Get form data
  $title = sanitizeInput($_POST['title'] ?? '');
  $slug = sanitizeInput($_POST['slug'] ?? '');
  $description = sanitizeInput($_POST['description'] ?? '');

  // Check if category exists
  $category = getSingleData('product_categories', 'id', $categoryId);

  if (!$category) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Category not found']);
    exit;
  }

  // Validate form data
  $validator = new Validator(
    [
      'title' => $title,
      'slug' => $slug,
      'description' => $description
    ],
    [
      'title' => 'required|string|max:255',
      'slug' => 'required|string|max:255',
      'description' => 'string'
    ]
  );

  if ($validator->fails()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => $validator->errors()]);
    exit;
  }

  // Handle image upload
  $imageName = $category['image'];
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $newImageName = uploadImage($_FILES['image']);
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

  // Update category
  $categoryData = [
    'title' => $title,
    'slug' => $slug,
    'description' => $description
  ];

  // Only update image if a new one was uploaded
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $categoryData['image'] = $imageName;
  }

  $result = updateData('product_categories', $categoryData, 'id', $categoryId);

  if ($result >= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    exit;
  } else {
    // Delete uploaded image if update failed
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      deleteUploadedImage($imageName);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to update category']);
    exit;
  }
}

// Update a specific field of a category
function updateCategoryField($categoryId)
{
  // Get field and value
  $field = sanitizeInput($_POST['field'] ?? '');
  $value = sanitizeInput($_POST['value'] ?? '');
  $categoryId = sanitizeInput($categoryId);

  // Validate field
  $allowedFields = ['title', 'description'];
  if (!in_array($field, $allowedFields)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
  }

  // Check if category exists
  $category = getSingleData('product_categories', 'id', $categoryId);

  if (!$category) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Category not found']);
    exit;
  }

  // Validate value based on field
  $validationRules = [
    'title' => 'required|string|max:255',
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

  // Update field
  $categoryData = [$field => $value];

  // If title is updated, also update the slug
  if ($field === 'title') {
    $categoryData['slug'] = createSlug($value);
  }

  $result = updateData('product_categories', $categoryData, 'id', $categoryId);

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

// Delete categories
function deleteCategories()
{
  // Get category IDs
  $ids = $_POST['ids'] ?? [];

  if (empty($ids)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No categories selected']);
    exit;
  }

  // Convert to array if string
  if (!is_array($ids)) {
    $ids = [$ids];
  }

  // Delete categories
  $successCount = 0;

  foreach ($ids as $id) {
    $id = sanitizeInput($id);

    // Get category image before deleting
    $category = getSingleData('product_categories', 'id', $id);

    $result = deleteData('product_categories', 'id', $id);
    if ($result > 0) {
      $successCount++;

      // Delete image if exists
      if ($category && $category['image']) {
        deleteUploadedImage($category['image']);
      }
    }
  }

  header('Content-Type: application/json');
  echo json_encode([
    'success' => true,
    'message' => $successCount . ' category(s) deleted successfully'
  ]);
  exit;
}

// Helper function to create slug
function createSlug($text)
{
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

// Helper function to upload image
function uploadImage($file)
{
  // Check if uploads directory exists, if not create it
  $uploadDir = BASE_PATH . '/public/uploads/product_categories/';
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
    return 'product_categories/' . $filename;
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
