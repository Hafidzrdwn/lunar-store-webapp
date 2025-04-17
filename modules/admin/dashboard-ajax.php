<?php
require_once __DIR__ . '/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

// Handle AJAX request for sales data
if (isset($_GET['action']) && $_GET['action'] === 'get-sales-data') {
  $period = $_GET['period'] ?? 'year';
  $sales_data = getSalesTrendData($period);

  header('Content-Type: application/json');
  echo json_encode($sales_data);
  exit;
}

/**
 * Get sales trend data
 * 
 * @param string $period
 * @return array
 */
function getSalesTrendData($period = 'year')
{
  global $conn;

  switch ($period) {
    case 'month':
      // Daily data for current month
      $query = "SELECT DATE_FORMAT(created_at, '%d %b') as label,
                      SUM(total_amount) as total
                      FROM orders
                      WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
                      AND YEAR(created_at) = YEAR(CURRENT_DATE())
                      GROUP BY DATE(created_at)
                      ORDER BY DATE(created_at)";
      break;

    case '6months':
      // Monthly data for last 6 months
      $query = "SELECT DATE_FORMAT(created_at, '%b %Y') as label,
                      SUM(total_amount) as total
                      FROM orders
                      WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                      GROUP BY YEAR(created_at), MONTH(created_at)
                      ORDER BY YEAR(created_at), MONTH(created_at)";
      break;

    case 'year':
    default:
      // Monthly data for current year
      $query = "SELECT DATE_FORMAT(created_at, '%b') as label,
                      SUM(total_amount) as total
                      FROM orders
                      WHERE YEAR(created_at) = YEAR(CURRENT_DATE())
                      GROUP BY MONTH(created_at)
                      ORDER BY MONTH(created_at)";
      break;
  }

  $result = mysqli_query($conn, $query);

  $labels = [];
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['label'];
    $data[] = (float) $row['total'];
  }

  return [
    'labels' => $labels,
    'data' => $data
  ];
}
