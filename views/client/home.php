<?php View::extend('client'); ?>
<?php View::startSection('title'); ?>
Homepage
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<?php
$categories = [
  [
    'id' => 1,
    'name' => 'Editing Apps',
    'slug' => 'editing-apps',
    'description' => 'Premium photo and video editing applications',
    'image' => 'poster_edit.png'
  ],
  [
    'id' => 2,
    'name' => 'Mobile Legends',
    'slug' => 'mobile-legends',
    'description' => 'Top up diamonds for Mobile Legends',
    'image' => 'poster_ml.png'
  ],
  [
    'id' => 3,
    'name' => 'Streaming Apps',
    'slug' => 'streaming-apps',
    'description' => 'Premium subscriptions for streaming services',
    'image' => 'poster_streaming.png'
  ],
  [
    'id' => 4,
    'name' => 'Education Apps',
    'slug' => 'education-apps',
    'description' => 'Get all the tools to boost your productivity',
    'image' => 'poster_edu.png'
  ]
];

$products = [
  [
    'id' => 1,
    'name' => 'Mobile Legends: 100 Diamonds',
    'description' => 'Top up 100 diamonds for Mobile Legends',
    'price' => 'Rp 25.000',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 2,
    'name' => 'Adobe Lightroom Premium',
    'description' => '1 month subscription for Adobe Lightroom',
    'price' => 'Rp 75.000',
    'image' => 'https://placehold.co/300x200'
  ],
  [
    'id' => 3,
    'name' => 'Netflix Premium 1 Month',
    'description' => '1 month subscription for Netflix Premium',
    'price' => 'Rp 120.000',
    'image' => 'https://placehold.co/300x200'
  ]
];

?>

<!-- Hero Section -->
<section
  class="relative py-20 text-white overflow-x-clip bg-gradient-to-r from-blue-500 to-blue-800">
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute left-0 right-0 top-0 bg-white/10 h-[1px]"></div>
    <div class="absolute left-0 right-0 bottom-0 bg-white/5 h-[1px]"></div>
    <div
      class="absolute rounded-full -left-40 -top-40 h-80 w-80 bg-white/10 blur-3xl"></div>
    <div
      class="absolute rounded-full -right-40 -bottom-40 h-80 w-80 bg-white/10 blur-3xl"></div>
  </div>
  <div class="container relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-14">
    <div class="lg:flex lg:items-center lg:justify-between">
      <div class="lg:w-1/2">
        <h1 class="mb-8 text-4xl font-bold md:text-5xl lg:text-6xl">
          Premium Digital Products
        </h1>
        <p class="mb-6 text-xl text-white">
          Get premium apps and game top-ups at the best prices
        </p>
        <div class="flex flex-col gap-4 sm:flex-row">
          <button type="button"
            class="px-6 py-3 text-lg font-medium text-blue-500 transition-all bg-white rounded-md active:scale-[0.9] hover:bg-blue-50">
            Browse Products <i class="ml-1 text-sm fas fa-angle-double-right"></i>
          </button>
        </div>
      </div>
      <div class="mt-10 lg:mt-0 lg:w-1/2">
        <div class="relative h-64 sm:h-72 md:h-80 lg:h-96">
          <!--This is moon animation-->
          <div class="transform -translate-x-40 -translate-y-80">
            <dotlottie-player src="https://lottie.host/07e69a38-119f-4261-a8e5-326f33bb2158/RY7A2B3V50.lottie" background="transparent" speed="1" style="width: 1150px; height: 1150px" loop autoplay></dotlottie-player>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="py-12 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
    <h2 class="text-3xl font-bold text-playfair text-blue-600">Categories</h2>
    <div class="bg-blue-600 w-[50px] h-[3px] mb-8 mt-2"></div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($categories as $category): ?>
        <a href="catalog.php?category=<?= $category['slug']; ?>" class="group">
          <div class="bg-blue-50 rounded-lg overflow-hidden shadow-md transition-transform pb-4 group-hover:scale-105">
            <div class="h-48 relative">
              <img src="<?= asset('client/images/' . $category['image']) ?>" alt="<?= $category['name']; ?>" class="object-cover w-full h-full">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-medium text-gray-900"><?= $category['name']; ?></h3>
              <p class="text-sm text-gray-600 mt-1"><?= $category['description']; ?></p>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-gradient-to-b from-white to-blue-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
    <h2 class="text-3xl font-bold text-playfair text-blue-600">Featured Products</h2>
    <div class="bg-blue-600 w-[50px] h-[3px] mb-8 mt-2"></div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($products as $product): ?>
        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
          <div class="h-48 relative">
            <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>" class="object-cover w-full h-full">
          </div>
          <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900"><?= $product['name']; ?></h3>
            <p class="text-sm text-gray-600 mt-1"><?= $product['description']; ?></p>
            <div class="mt-6 flex items-center justify-between">
              <span class="text-blue-500 font-bold"><?= $product['price']; ?></span>
              <button onclick="addToCart(<?= $product['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-600 transition-all active:scale-[0.9]">
                <i class="fas fa-eye mr-2"></i>View Details
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-8 text-center">
      <a href="catalog.php" class="inline-block border border-blue-500 text-blue-500 px-6 py-3 rounded-md font-medium hover:bg-blue-500 hover:text-white transition-all active:scale-[0.9]">
        View All Products <i class="ml-1 text-sm fas fa-angle-double-right"></i>
      </a>
    </div>
  </div>
</section>
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<?php if (isset($_SESSION['success'])): ?>
  <script>
    $(document).ready(function() {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '<?= $_SESSION['success']; ?>',
        confirmButtonColor: '#3B82F6'
      });
    });
  </script>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
<?php View::endSection(); ?>