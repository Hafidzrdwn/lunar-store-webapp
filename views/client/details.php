<?php View::extend('client'); ?>
<?php View::startSection('title'); ?>
Detail Produk
<?php View::endSection(); ?>
<?php View::startSection('custom_css'); ?>
<style>
  .plan-card {
    transition: all 0.3s ease;
  }

  .plan-card:hover {
    transform: translateY(-5px);
  }

  .plan-card.selected {
    border-color: #004aad;
    background-color: rgba(0, 74, 173, 0.05);
  }

  .duration-option {
    transition: all 0.2s ease;
    cursor: pointer;
    border: 1px solid #e5e7eb;
    background-color: white;
    user-select: none;
    -webkit-user-select: none;
  }

  .duration-option:hover {
    border-color: #004aad;
  }

  .duration-option.selected {
    background-color: #e6f0ff;
    border-color: #004aad;
  }

  .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 50;
    justify-content: center;
    align-items: center;
  }

  .modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 0.5rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    position: relative;
  }

  .modal.active {
    display: flex;
  }

  /* Add animation for the success modal */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .modal.active .modal-content {
    animation: fadeIn 0.3s ease-out forwards;
  }
</style>
<?php View::endSection(); ?>
<?php
global $conn;
// Initialize variables
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$addedToCart = isset($_POST['add_to_cart']) && $_POST['add_to_cart'] == 1;
$cartMessage = "Product has been added to your cart!";

// Escape the product_id for use in the query
$escaped_product_id = mysqli_real_escape_string($conn, $product_id);

// Improved query to get product details with direct variable interpolation
$productQuery = "SELECT p.*, pc.title 
                FROM products p
                LEFT JOIN product_categories pc ON p.category_id = pc.id
                WHERE p.id = {$escaped_product_id} AND p.ready_stock = 1";

// Get product details
$product = queryOne($productQuery);

if (!$product) {
  // Handle product not found
  redirect('/catalog');
  exit;
}

// Get product types and pricing details
$detailsQuery = "SELECT pd.*, pt.type_name, pt.description as type_description
                FROM product_details pd
                JOIN product_types pt ON pd.product_type_id = pt.id
                WHERE pd.product_id = {$escaped_product_id}
                ORDER BY pt.type_name, pd.duration";

$productDetails = stmt_query($detailsQuery);

// Organize product details into plans
$plans = [];
foreach ($productDetails as $detail) {
  $planId = 'plan_' . $detail['product_type_id'];
  $durationId = 'duration_' . $detail['id'];

  // Initialize plan if it doesn't exist
  if (!isset($plans[$planId])) {
    $plans[$planId] = [
      'name' => $detail['type_name'],
      'description' => $detail['type_description'] ?? 'Premium subscription plan',
      'durations' => []
    ];
  }

  // Add duration option
  $durationText = $detail['duration'] . ' ' . $detail['duration_unit'];
  $plans[$planId]['durations'][$durationId] = [
    'name' => $durationText,
    'price' => $detail['price'],
    'detail_id' => $detail['id']
  ];
}

$selectedPlan = isset($_POST['plan']) ? $_POST['plan'] : '';
$selectedDuration = isset($_POST['duration']) ? $_POST['duration'] : '';
$totalPrice = 0;

// Calculate total price if plan and duration are selected
if ($selectedPlan && $selectedDuration && isset($plans[$selectedPlan]['durations'][$selectedDuration])) {
  $totalPrice = $plans[$selectedPlan]['durations'][$selectedDuration]['price'];
}

// Handle add to cart
if ($addedToCart && $selectedPlan && $selectedDuration) {
  $detailId = $plans[$selectedPlan]['durations'][$selectedDuration]['detail_id'];

  // Add to cart logic here
  // Example: addToCart($product_id, $detailId, 1);

  // Set success message
  $cartMessage = "{$product['app_name']} has been added to your cart!";
}
?>
<?php View::startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14 py-8">
  <!-- Product Header -->
  <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-6 flex flex-col md:flex-row items-center">
      <div class="md:w-1/4 flex justify-center mb-6 md:mb-0">
        <img src="https://placehold.co/300x200"
          alt="<?= htmlspecialchars($product['app_name']) ?>"
          class="h-40 w-40 object-contain">
      </div>
      <div class="md:w-3/4 md:pl-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($product['app_name']) ?></h1>
        <div class="category-title mb-4">
          <span class="text-sm text-gray-500"><?= htmlspecialchars($product['title']) ?></span>
        </div>
        <p class="text-gray-600 mb-4">
          <?= htmlspecialchars($product['description'] ?? 'Enjoy premium access to this streaming service with our subscription plans.') ?>
        </p>
        <div class="flex flex-wrap gap-4">
          <?php if (!empty($product['features'])): ?>
            <?php $features = explode(',', $product['features']); ?>
            <?php foreach ($features as $feature): ?>
              <div class="flex items-center">
                <i class="fas fa-check text-primary mr-2"></i>
                <span class="text-sm"><?= htmlspecialchars(trim($feature)) ?></span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="flex items-center">
              <i class="fas fa-check text-primary mr-2"></i>
              <span class="text-sm">Premium Quality</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check text-primary mr-2"></i>
              <span class="text-sm">Multiple Devices</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check text-primary mr-2"></i>
              <span class="text-sm">24/7 Support</span>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <form method="post" action="" id="subscriptionForm">
    <!-- Subscription Options -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-900 category-title mb-6">Choose Your Plan</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($plans as $planId => $plan): ?>
            <div class="plan-card border rounded-lg p-5 <?= $selectedPlan === $planId ? 'selected' : 'border-gray-200'; ?>"
              id="plan-card-<?= $planId; ?>">
              <div class="flex items-start">
                <input type="radio" name="plan" id="plan_<?= $planId; ?>" value="<?= $planId; ?>"
                  <?= $selectedPlan === $planId ? 'checked' : ''; ?>
                  class="mt-1 mr-3 plan-radio">
                <div class="w-full">
                  <label for="plan_<?= $planId; ?>" class="font-semibold text-gray-800 block cursor-pointer text-lg">
                    <?= htmlspecialchars($plan['name']); ?>
                  </label>
                  <p class="text-sm text-gray-600 mt-1 mb-3"><?= htmlspecialchars($plan['description']); ?></p>

                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-4 duration-container" id="durations-<?= $planId; ?>">
                    <?php foreach ($plan['durations'] as $durationId => $duration): ?>
                      <div class="duration-option rounded-md p-3 flex justify-between items-center
                                <?= ($selectedPlan === $planId && $selectedDuration === $durationId) ? 'selected' : ''; ?>"
                        data-plan="<?= $planId; ?>" data-duration="<?= $durationId; ?>">
                        <input type="radio" name="duration" id="duration_<?= $planId; ?>_<?= $durationId; ?>"
                          value="<?= $durationId; ?>"
                          <?= ($selectedPlan === $planId && $selectedDuration === $durationId) ? 'checked' : ''; ?>
                          class="hidden duration-radio">
                        <span class="text-sm"><?= htmlspecialchars($duration['name']); ?></span>
                        <span class="font-semibold text-primary"><?= toRupiah($duration['price']); ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-6">
          <button type="submit" id="addToCartBtn"
            class="w-full bg-primary hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 flex items-center justify-center <?= ($selectedPlan && $selectedDuration) ? '' : 'opacity-50 cursor-not-allowed'; ?>"
            <?= ($selectedPlan && $selectedDuration) ? '' : 'disabled'; ?>>
            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
          </button>
        </div>
      </div>
    </div>

    <input type="hidden" name="add_to_cart" value="1" id="addToCartInput">
  </form>
</div>

<!-- Success Modal -->
<div class="modal <?= $addedToCart ? 'active' : ''; ?>" id="successModal">
  <div class="modal-content">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-900">Success!</h3>
      <button type="button" class="close-modal text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="mb-6">
      <div class="flex items-center justify-center mb-4">
        <div class="bg-green-100 text-green-500 rounded-full p-3">
          <i class="fas fa-check-circle text-3xl"></i>
        </div>
      </div>
      <p class="text-center text-gray-700"><?= htmlspecialchars($cartMessage); ?></p>
      <?php if ($addedToCart && $selectedPlan && $selectedDuration): ?>
        <p class="text-center text-gray-500 text-sm mt-2" id="planDetails">
          <?php
          echo htmlspecialchars($plans[$selectedPlan]['name']) . ' - ' .
            htmlspecialchars($plans[$selectedPlan]['durations'][$selectedDuration]['name']) . ' (' .
            toRupiah($plans[$selectedPlan]['durations'][$selectedDuration]['price']) . ')';
          ?>
        </p>
      <?php endif; ?>
    </div>
    <div class="flex justify-between">
      <button type="button" class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-200">
        Continue Shopping
      </button>
      <a href="cart.php" class="bg-primary hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
        Go to Cart
      </a>
    </div>
  </div>
</div>
<?php View::endSection(); ?>
<?php View::startSection('custom_js'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const planCards = document.querySelectorAll('.plan-card');
    const planRadios = document.querySelectorAll('.plan-radio');
    const durationOptions = document.querySelectorAll('.duration-option');
    const durationContainers = document.querySelectorAll('.duration-container');
    const form = document.getElementById('subscriptionForm');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const successModal = document.getElementById('successModal');
    const planDetailsElement = document.getElementById('planDetails');
    const closeModalButtons = document.querySelectorAll('.close-modal');

    // Initially hide all duration containers except for the selected plan
    updateDurationVisibility();

    // Handle plan selection
    planCards.forEach(card => {
      card.addEventListener('click', function() {
        const planId = this.id.replace('plan-card-', '');
        const planRadio = document.getElementById('plan_' + planId);

        // Select the plan
        planRadio.checked = true;

        // Update UI for plans
        planCards.forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');

        // Show durations for this plan only
        updateDurationVisibility();
      });
    });

    // Handle duration selection
    durationOptions.forEach(option => {
      option.addEventListener('click', function() {
        const planId = this.dataset.plan;
        const durationId = this.dataset.duration;
        const durationRadio = document.getElementById('duration_' + planId + '_' + durationId);

        // Make sure the plan is selected first
        const planRadio = document.getElementById('plan_' + planId);
        planRadio.checked = true;

        // Update UI for plans
        planCards.forEach(c => c.classList.remove('selected'));
        document.getElementById('plan-card-' + planId).classList.add('selected');

        // Deselect all durations across all plans
        durationOptions.forEach(opt => {
          opt.classList.remove('selected');
          const radio = opt.querySelector('input[type="radio"]');
          if (radio) radio.checked = false;
        });

        // Select this duration
        durationRadio.checked = true;
        this.classList.add('selected');

        // Enable the Add to Cart button
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
      });
    });

    // Close modal when close button is clicked
    closeModalButtons.forEach(button => {
      button.addEventListener('click', function() {
        successModal.classList.remove('active');
      });
    });

    // Close modal when clicking outside
    successModal.addEventListener('click', function(e) {
      if (e.target === successModal) {
        successModal.classList.remove('active');
      }
    });

    // Function to update which duration options are visible
    function updateDurationVisibility() {
      const selectedPlan = document.querySelector('input[name="plan"]:checked');

      if (selectedPlan) {
        const planId = selectedPlan.value;

        // Hide all duration containers
        durationContainers.forEach(container => {
          if (container.id === `durations-${planId}`) {
            container.style.display = 'grid';
          } else {
            container.style.display = 'none';
          }
        });
      }
    }

    // If the modal is active on page load (PHP added the class), set a timeout to auto-hide it
    if (successModal.classList.contains('active')) {
      setTimeout(() => {
        successModal.classList.remove('active');
      }, 5000); // Hide after 5 seconds
    }
  });
</script>
<?php View::endSection(); ?>