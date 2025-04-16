<?php View::extend('client'); ?>
<?php View::startSection('title'); ?>
Product Catalog
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<?php
// Get category from URL parameter
$current_category = isset($_GET['category']) ? $_GET['category'] : '';

// Sample data for categories
$categories = [
  'editing-apps' => 'Editing Apps',
  'mobile-legends' => 'Mobile Legends',
  'streaming-apps' => 'Streaming Apps',
  'productivity-apps' => 'Productivity Apps'
];

// Sample data for products
$editing_apps = [
  [
    'id' => 101,
    'name' => 'Adobe Lightroom Premium',
    'description' => 'Professional photo editing app',
    'price' => 'Rp 75.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 102,
    'name' => 'Adobe Photoshop Premium',
    'description' => 'Professional image editing software',
    'price' => 'Rp 99.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 103,
    'name' => 'Canva Pro',
    'description' => 'Design platform for social media & more',
    'price' => 'Rp 65.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 104,
    'name' => 'Filmora Pro',
    'description' => 'Video editing software',
    'price' => 'Rp 85.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 105,
    'name' => 'Snapseed Pro',
    'description' => 'Mobile photo editing app',
    'price' => 'Rp 45.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 106,
    'name' => 'VSCO Premium',
    'description' => 'Photo & video editing with presets',
    'price' => 'Rp 55.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ]
];

$mobile_legends = [
  [
    'id' => 201,
    'name' => '86 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 22.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 202,
    'name' => '172 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 42.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 203,
    'name' => '257 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 62.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 204,
    'name' => '344 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 82.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 205,
    'name' => '429 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 102.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 206,
    'name' => '514 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 122.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 207,
    'name' => '706 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 162.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 208,
    'name' => '878 Diamonds',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 202.000',
    'period' => '',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 209,
    'name' => 'Starlight Member',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 149.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 210,
    'name' => 'Starlight Member Plus',
    'description' => 'Mobile Legends: Bang Bang',
    'price' => 'Rp 290.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ]
];

$streaming_apps = [
  [
    'id' => 301,
    'name' => 'Netflix Premium',
    'description' => 'Streaming service for movies & TV shows',
    'price' => 'Rp 120.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 302,
    'name' => 'Spotify Premium',
    'description' => 'Music streaming service',
    'price' => 'Rp 55.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 303,
    'name' => 'Disney+ Hotstar',
    'description' => 'Streaming service for Disney content',
    'price' => 'Rp 39.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ]
];

$productivity_apps = [
  [
    'id' => 401,
    'name' => 'Microsoft 365',
    'description' => 'Office suite with Word, Excel, PowerPoint',
    'price' => 'Rp 149.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 402,
    'name' => 'Notion Premium',
    'description' => 'All-in-one workspace',
    'price' => 'Rp 85.000',
    'period' => '1 Month',
    'image' => 'https://placehold.co/300x200'
  ]
];

// Determine which products to display based on category
$products_to_display = [];
$category_title = 'All Products';

if ($current_category == 'editing-apps') {
  $products_to_display = $editing_apps;
  $category_title = 'Editing Apps';
} elseif ($current_category == 'mobile-legends') {
  $products_to_display = $mobile_legends;
  $category_title = 'Mobile Legends';
} elseif ($current_category == 'streaming-apps') {
  $products_to_display = $streaming_apps;
  $category_title = 'Streaming Apps';
} elseif ($current_category == 'productivity-apps') {
  $products_to_display = $productivity_apps;
  $category_title = 'Productivity Apps';
} else {
  // If no category is selected, show all products
  $products_to_display = array_merge($editing_apps, $mobile_legends, $streaming_apps, $productivity_apps);
}

// Pagination settings
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_items = count($products_to_display);
$total_pages = ceil($total_items / $items_per_page);

// Ensure current page is valid
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;

// Get products for current page
$offset = ($current_page - 1) * $items_per_page;
$products_to_display = array_slice($products_to_display, $offset, $items_per_page);
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
      <a href="javascript:void(0)" data-category="" class="category-filter <?php echo $current_category == '' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
        All Products
      </a>
      <?php foreach ($categories as $slug => $name): ?>
        <a href="javascript:void(0)" data-category="<?php echo $slug; ?>" class="category-filter <?php echo $current_category == $slug ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
          <?php echo $name; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Product Catalog -->
<section class="py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
    <h2 class="text-xl md:text-2xl font-bold text-blue-600 text-playfair" id="category-title"><?php echo $category_title; ?></h2>
    <div class="bg-blue-600 w-[50px] h-[3px] mb-8 mt-2"></div>

    <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php foreach ($products_to_display as $product): ?>
        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow h-full flex flex-col">
          <div class="h-48 relative">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="object-cover w-full h-full">
          </div>
          <div class="p-4 flex flex-col flex-grow">
            <h3 class="text-lg font-medium text-gray-900"><?php echo $product['name']; ?></h3>
            <p class="text-sm text-gray-600 mt-1"><?php echo $product['description']; ?></p>
            <?php if (!empty($product['period'])): ?>
              <p class="text-xs text-gray-500 mt-1"><?php echo $product['period']; ?></p>
            <?php endif; ?>
            <div class="mt-auto pt-4 flex items-center justify-between">
              <span class="text-blue-500 font-bold"><?php echo $product['price']; ?></span>
              <button onclick="addToCart(<?php echo $product['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                <i class="fas fa-eye mr-2"></i>View Detail
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <div class="mt-10 flex justify-center" id="pagination">
        <div class="flex space-x-2">
          <?php if ($current_page > 1): ?>
            <a href="javascript:void(0)" data-page="<?php echo $current_page - 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
              Previous
            </a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="javascript:void(0)" data-page="<?php echo $i; ?>" class="pagination-link <?php echo $i == $current_page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>

          <?php if ($current_page < $total_pages): ?>
            <a href="javascript:void(0)" data-page="<?php echo $current_page + 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
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

      // Show loading state
      $('#products-container').html('<div class="col-span-full text-center py-10"><i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i></div>');

      // Make AJAX request
      // $.ajax({
      //   url: 'catalog.php',
      //   type: 'GET',
      //   data: {
      //     category: category,
      //     ajax: true,
      //     page: 1 // Reset to first page when changing category
      //   },
      //   success: function(response) {
      //     // Update URL without refreshing page
      //     var newUrl = 'catalog.php' + (category ? '?category=' + category : '');
      //     history.pushState({}, '', newUrl);

      //     // Parse the JSON response
      //     var data = JSON.parse(response);

      //     // Update the category title
      //     $('#category-title').text(data.category_title);

      //     // Update the products container
      //     $('#products-container').html(data.products_html);

      //     // Update pagination
      //     $('#pagination').html(data.pagination_html);

      //     // Reattach pagination event handlers
      //     attachPaginationHandlers();
      //   },
      //   error: function() {
      //     $('#products-container').html('<div class="col-span-full text-center py-10">Error loading products. Please try again.</div>');
      //   }
      // });
    });

    // Function to attach pagination event handlers
    function attachPaginationHandlers() {
      $('.pagination-link').on('click', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        var category = new URLSearchParams(window.location.search).get('category') || '';

        // Show loading state
        $('#products-container').html('<div class="col-span-full text-center py-10"><i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i></div>');

        // Make AJAX request
        // $.ajax({
        //   url: 'catalog.php',
        //   type: 'GET',
        //   data: {
        //     category: category,
        //     ajax: true,
        //     page: page
        //   },
        //   success: function(response) {
        //     // Update URL without refreshing page
        //     var newUrl = 'catalog.php?page=' + page + (category ? '&category=' + category : '');
        //     history.pushState({}, '', newUrl);

        //     // Parse the JSON response
        //     var data = JSON.parse(response);

        //     // Update the products container
        //     $('#products-container').html(data.products_html);

        //     // Update pagination
        //     $('#pagination').html(data.pagination_html);

        //     // Reattach pagination event handlers
        //     attachPaginationHandlers();
        //   },
        //   error: function() {
        //     $('#products-container').html('<div class="col-span-full text-center py-10">Error loading products. Please try again.</div>');
        //   }
        // });
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
    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow h-full flex flex-col">
      <div class="h-48 relative">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="object-cover w-full h-full">
      </div>
      <div class="p-4 flex flex-col flex-grow">
        <h3 class="text-lg font-medium text-gray-900"><?php echo $product['name']; ?></h3>
        <p class="text-sm text-gray-600 mt-1"><?php echo $product['description']; ?></p>
        <?php if (!empty($product['period'])): ?>
          <p class="text-xs text-gray-500 mt-1"><?php echo $product['period']; ?></p>
        <?php endif; ?>
        <div class="mt-auto pt-4 flex items-center justify-between">
          <span class="text-blue-500 font-bold"><?php echo $product['price']; ?></span>
          <button onclick="addToCart(<?php echo $product['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
            <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
          </button>
        </div>
      </div>
    </div>
  <?php endforeach;
  $response['products_html'] = ob_get_clean();

  // Generate pagination HTML
  if ($total_pages > 1) {
    ob_start(); ?>
    <div class="flex space-x-2">
      <?php if ($current_page > 1): ?>
        <a href="javascript:void(0)" data-page="<?php echo $current_page - 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
          Previous
        </a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="javascript:void(0)" data-page="<?php echo $i; ?>" class="pagination-link <?php echo $i == $current_page ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium transition-colors">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages): ?>
        <a href="javascript:void(0)" data-page="<?php echo $current_page + 1; ?>" class="pagination-link bg-gray-100 text-gray-800 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium transition-colors">
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