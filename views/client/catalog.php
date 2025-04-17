<?php View::extend('client'); ?>
<?php View::startSection('title'); ?>
Product Catalog
<?php View::endSection(); ?>

<?php View::startSection('custom_css'); ?>
<style>
  .out_of_stock {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .disabled-btn-view {
    pointer-events: none;
  }
</style>
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<?php
global $conn;

// Get category from URL parameter
$current_category = isset($_GET['category']) ? $_GET['category'] : '';
$current_category_data = getSingleData('product_categories', 'slug', $conn->real_escape_string($current_category));

$categories = query("SELECT slug, title FROM product_categories ORDER BY id ASC");

// Determine which products to display based on category
$category_title = 'All Products';
$products_to_display = [];

// Build query with JOIN to get starting price
$query = "SELECT 
            p.id, 
            p.app_name, 
            p.description, 
            p.ready_stock,
            MIN(pd.price) AS starting_price
          FROM products p
          LEFT JOIN product_details pd ON pd.product_id = p.id";

if (!empty($current_category)) {
  $query .= " WHERE p.category_id = " . $current_category_data['id'];
  $category_title = $current_category_data['title'];
}

$query .= " GROUP BY p.id ORDER BY p.app_name";

// Pagination settings
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Count total items for pagination (without join)
$count_query = "SELECT COUNT(*) as total FROM products p";
if (!empty($current_category)) {
  $count_query .= " WHERE p.category_id = " . $current_category_data['id'];
}
$count_result = $conn->query($count_query);
$total_items = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Ensure current page is valid
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;

// Add pagination to query
$offset = ($current_page - 1) * $items_per_page;
$query .= " LIMIT $offset, $items_per_page";

// Fetch products
$result = $conn->query($query);
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $row['starting_price_formatted'] = $row['starting_price'] !== null ? toRupiah($row['starting_price']) : '-';
    $products_to_display[] = $row;
  }
}
?>


<!-- Catalog Header -->
<div class="bg-white shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14 py-6">
    <h1 class="lunar-text text-2xl md:text-3xl font-bold text-blue-500">PRODUCT CATALOG</h1>
    <p class="text-gray-600 mt-2">Browse our collection of premium digital products.</p>
  </div>
</div>

<!-- Category Filter -->
<div class="bg-white border-t border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14 py-4">
    <div class="flex flex-wrap items-center gap-3" id="category-filters">
      <a href="javascript:void(0)" data-category="" class="category-filter <?= $current_category == '' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
        All Products
      </a>
      <?php foreach ($categories as $category): ?>
        <a href="javascript:void(0)" data-category="<?= $category['slug']; ?>" class="category-filter <?= $current_category == $category['slug'] ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
          <?= $category['title']; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Product Catalog -->
<section class="py-12 bg-gradient-to-b from-blue-50 to-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
    <h2 class="text-xl md:text-2xl font-bold text-blue-600 text-playfair" id="category-title"><?= $category_title; ?></h2>
    <div class="bg-blue-600 w-[50px] h-[3px] mb-8 mt-2"></div>

    <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php foreach ($products_to_display as $product): ?>
        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col h-[440px] relative <?php if (!$product['ready_stock']): ?> out_of_stock <?php endif; ?>">
          <div class="h-48 relative">
            <img src="https://placehold.co/300x200" alt="<?= $product['app_name']; ?>" class="object-cover w-full h-full">
          </div>
          <div class="p-6 pb-24">
            <h3 class="text-lg font-semibold text-gray-900"><?= $product['app_name']; ?></h3>

            <!-- Truncate description in PHP and style with ellipsis -->
            <p class="text-sm text-gray-600 mt-1 overflow-hidden line-clamp-3">
              <?= strlen($product['description']) > 100 ? substr($product['description'], 0, 150) . '...' : $product['description']; ?>
            </p>
          </div>

          <!-- Absolutely positioned price and button row -->
          <div class="absolute bottom-0 left-0 right-0 p-6">
            <div class="flex flex-col items-start mb-2">
              <span class="text-blue-500 font-medium text-sm">Starting From</span>
              <span class="text-lg font-semibold text-blue-500"><?= $product['starting_price_formatted']; ?></span>
            </div>
            <?php
            $class_btn = ($product['ready_stock']) ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-red-100 text-red-500 disabled-btn-view';
            ?>
            <a href="<?= site_url('/details?id=' . $product['id']); ?>" class="px-4 py-2 rounded-md text-sm block text-center transition-all active:scale-[0.9] <?= $class_btn; ?>">
              <?php if ($product['ready_stock']): ?>
                <i class="fas fa-eye mr-2"></i>View Details
              <?php else: ?>
                <i class="fas fa-ban mr-2"></i>Out of Stock
              <?php endif; ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <div class="mt-10 flex justify-center" id="pagination">
        <div class="flex space-x-2">
          <?php if ($current_page > 1): ?>
            <a href="javascript:void(0)" data-page="<?= $current_page - 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
              Previous
            </a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="javascript:void(0)" data-page="<?= $i; ?>" class="pagination-link <?= $i == $current_page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
              <?= $i; ?>
            </a>
          <?php endfor; ?>

          <?php if ($current_page < $total_pages): ?>
            <a href="javascript:void(0)" data-page="<?= $current_page + 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
              Next
            </a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<script>
  $(document).ready(function() {
    // Category filter click handler
    $('.category-filter').on('click', function(e) {
      e.preventDefault();
      var category = $(this).data('category');

      // Update active class
      $('.category-filter').removeClass('bg-blue-500 text-white').addClass('bg-gray-100 text-gray-800 hover:bg-gray-200');
      $(this).removeClass('bg-gray-100 text-gray-800 hover:bg-gray-200').addClass('bg-blue-500 text-white');

      // Fade out current content
      $('#products-container').fadeOut(300, function() {
        // Show loading state after fade out
        $(this).html('<div class="col-span-full text-center py-10"><i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i></div>').fadeIn(300);

        // Make AJAX request
        $.ajax({
          url: '<?= site_url('/catalog') ?>',
          type: 'GET',
          data: {
            category: category,
            ajax: true,
            page: 1
          },
          success: function(response) {
            // Update URL without refreshing page
            var newUrl = 'catalog' + (category ? '?category=' + category : '');
            history.pushState({}, '', newUrl);

            // Parse the JSON response
            var data = JSON.parse(JSON.stringify(response));

            // Update the category title with fade effect
            $('#category-title').fadeOut(200, function() {
              $(this).text(data.category_title).fadeIn(200);
            });

            // Update the products container with fade effect
            $('#products-container').fadeOut(200, function() {
              $(this).html(data.products_html).fadeIn(400);
            });

            // Instead of modifying pagination links, let's just reload the page
            // This will ensure pagination works correctly and avoids footer movement issues
            setTimeout(function() {
              window.location.href = newUrl;
            }, 600); // Wait for fade effects to complete
          },
          error: function() {
            $('#products-container').fadeOut(200, function() {
              $(this).html('<div class="col-span-full text-center py-10">Error loading products. Please try again.</div>').fadeIn(400);
            });
          }
        });
      });
    });

    // Function to attach pagination event handlers
    function attachPaginationHandlers() {
      $('.pagination-link').on('click', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        var category = new URLSearchParams(window.location.search).get('category') || '';

        // Show loading state
        $('#products-container').html('<div class="col-span-full text-center py-10"><i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i></div>');

        window.location.href = '<?= site_url('/catalog') ?>?category=' + category + '&page=' + page;
      });
    }

    // Initial attachment of pagination handlers
    attachPaginationHandlers();
  });
</script>
<?php View::endSection(); ?>

<?php
// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
  $response = [
    'category_title' => $category_title,
    'products_html' => '',
    'pagination_html' => ''
  ];

  // Generate products HTML
  ob_start();
  foreach ($products_to_display as $product): ?>
    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col h-[440px] relative">
      <div class="h-48 relative">
        <img src="https://placehold.co/300x200" alt="<?= $product['app_name']; ?>" class="object-cover w-full h-full">
      </div>
      <div class="p-6 pb-24">
        <h3 class="text-lg font-semibold text-gray-900"><?= $product['app_name']; ?></h3>

        <!-- Truncate description in PHP and style with ellipsis -->
        <p class="text-sm text-gray-600 mt-1 overflow-hidden line-clamp-3">
          <?= strlen($product['description']) > 100 ? substr($product['description'], 0, 150) . '...' : $product['description']; ?>
        </p>
      </div>

      <!-- Absolutely positioned price and button row -->
      <div class="absolute bottom-0 left-0 right-0 p-6">
        <div class="flex flex-col items-start mb-2">
          <span class="text-blue-500 font-medium text-sm">Starting From</span>
          <span class="text-lg font-semibold text-blue-500">Rp<?= number_format(3500, 0, ',', '.'); ?></span>
        </div>
        <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm block text-center hover:bg-blue-600 transition-all active:scale-[0.9]">
          <i class="fas fa-eye mr-2"></i>View Details
        </a>
      </div>
    </div>
  <?php endforeach;
  $response['products_html'] = ob_get_clean();

  // Generate pagination HTML
  if ($total_pages > 1) {
    ob_start(); ?>
    <div class="flex space-x-2">
      <?php if ($current_page > 1): ?>
        <a href="javascript:void(0)" data-page="<?= $current_page - 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
          Previous
        </a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="javascript:void(0)" data-page="<?= $i; ?>" class="pagination-link <?= $i == $current_page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
          <?= $i; ?>
        </a>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages): ?>
        <a href="javascript:void(0)" data-page="<?= $current_page + 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
          Next
        </a>
      <?php endif; ?>
    </div>
<?php
    $response['pagination_html'] = ob_get_clean();
  }

  // Return JSON response and exit
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}
?>