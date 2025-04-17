<?php

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header('Location: ' . site_url('/admin/login'));
  exit;
}

// Handle AJAX request for chart data
if (isset($_GET['action']) && $_GET['action'] === 'get-chart-data') {
  $type = $_GET['type'] ?? 'sales';
  $chart_data = ($type === 'sales') ? getSalesTrendData() : getUsersTrendData();

  header('Content-Type: application/json');
  echo json_encode($chart_data);
  exit;
}

// Get dashboard data
$total_users = getTotalUsers();
$total_products = getTotalProducts();
$total_categories = getTotalCategories();
$total_revenue = getEstimatedRevenue();
$popular_categories = getPopularCategories();
$topup_games = getTopupGames();
$product_types = getProductTypes();
$latest_products = getLatestProducts(5);
$recent_users = getRecentUsers(3);
$sales_trend = getSalesTrendData();

/**
 * Get total number of users
 * 
 * @return int
 */
function getTotalUsers()
{
  global $conn;
  $query = "SELECT COUNT(*) as total FROM users";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['total'] ?? 0;
}

/**
 * Get total number of products
 * 
 * @return int
 */
function getTotalProducts()
{
  global $conn;
  $query = "SELECT COUNT(*) as total FROM products";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['total'] ?? 0;
}

/**
 * Get total number of categories
 * 
 * @return int
 */
function getTotalCategories()
{
  global $conn;
  $query = "SELECT COUNT(*) as total FROM product_categories";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['total'] ?? 0;
}

/**
 * Get estimated total revenue
 * 
 * @return float
 */
function getEstimatedRevenue()
{
  global $conn;
  $query = "SELECT SUM(price) as total FROM product_details";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['total'] ?? 0;
}

/**
 * Get popular categories with product counts
 * 
 * @return array
 */
function getPopularCategories()
{
  global $conn;
  $query = "SELECT pc.id, pc.title, pc.image, COUNT(p.id) as product_count
              FROM product_categories pc
              LEFT JOIN products p ON pc.id = p.category_id
              GROUP BY pc.id
              ORDER BY product_count DESC
              LIMIT 5";

  $result = mysqli_query($conn, $query);

  $categories = [];
  $total_products = 0;

  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
    $total_products += $row['product_count'];
  }

  // Calculate percentage for each category
  foreach ($categories as &$category) {
    $category['percentage'] = ($total_products > 0) ?
      round(($category['product_count'] / $total_products) * 100) : 0;
  }

  return $categories;
}

/**
 * Get top-up games data
 * 
 * @return array
 */
function getTopupGames()
{
  global $conn;
  $query = "SELECT * FROM topup_games ORDER BY diamond ASC";
  $result = mysqli_query($conn, $query);

  $games = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $games[] = $row;
  }

  return $games;
}

/**
 * Get product types with counts
 * 
 * @return array
 */
function getProductTypes()
{
  global $conn;
  $query = "SELECT pt.id, pt.type_name, COUNT(pd.id) as count
              FROM product_types pt
              LEFT JOIN product_details pd ON pt.id = pd.product_type_id
              GROUP BY pt.id
              ORDER BY count DESC";

  $result = mysqli_query($conn, $query);

  $types = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $types[] = $row;
  }

  return $types;
}

/**
 * Get latest products with category and price range
 * 
 * @param int $limit
 * @return array
 */
function getLatestProducts($limit = 5)
{
  global $conn;
  $query = "SELECT 
            p.id, 
            p.app_name, 
            p.description, 
            p.cover_img, 
            p.has_type,
            pc.title AS category_title, 
            MIN(pd.price) AS min_price, 
            MAX(pd.price) AS max_price,
            MAX(pd.created_at) AS latest_created_at
          FROM products p
          JOIN product_categories pc ON p.category_id = pc.id
          JOIN product_details pd ON p.id = pd.product_id
          GROUP BY p.id
          ORDER BY latest_created_at DESC
          LIMIT ?";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'i', $limit);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $products = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }

  return $products;
}

/**
 * Get recent users
 * 
 * @param int $limit
 * @return array
 */
function getRecentUsers($limit = 3)
{
  global $conn;
  $query = "SELECT id, username, name, email, created_at
              FROM users
              ORDER BY created_at DESC
              LIMIT ?";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'i', $limit);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $users = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
  }

  return $users;
}

/**
 * Get sales trend data
 * 
 * @return array
 */
function getSalesTrendData()
{
  // Since we don't have actual sales data in the database,
  // we'll generate some sample data for the chart
  $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  $current_month = date('n') - 1; // 0-based index

  $labels = [];
  $data = [];

  // Get the last 6 months
  for ($i = 5; $i >= 0; $i--) {
    $month_index = ($current_month - $i + 12) % 12;
    $labels[] = $months[$month_index];

    // Generate random sales data between 5M and 20M
    $data[] = rand(5000000, 20000000);
  }

  return [
    'labels' => $labels,
    'data' => $data
  ];
}

/**
 * Get users trend data
 * 
 * @return array
 */
function getUsersTrendData()
{
  global $conn;

  // Get the last 6 months of user registrations
  $query = "SELECT DATE_FORMAT(created_at, '%b') as month, 
              COUNT(*) as count
              FROM users
              WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
              GROUP BY YEAR(created_at), MONTH(created_at)
              ORDER BY YEAR(created_at), MONTH(created_at)";

  $result = mysqli_query($conn, $query);

  $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  $current_month = date('n') - 1; // 0-based index

  // Initialize data array with zeros
  $data = array_fill(0, 6, 0);
  $labels = [];

  // Get the last 6 months
  for ($i = 5; $i >= 0; $i--) {
    $month_index = ($current_month - $i + 12) % 12;
    $labels[] = $months[$month_index];
  }

  // Fill in actual data where available
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $month = $row['month'];
      $count = $row['count'];

      $index = array_search($month, $labels);
      if ($index !== false) {
        $data[$index] = (int) $count;
      }
    }
  }

  return [
    'labels' => $labels,
    'data' => $data
  ];
}
