<?php
function is_active($uri)
{
  return current_url() === $uri ? 'text-blue-600 font-semibold' : 'text-gray-700';
}
?>

<!-- Navigation -->
<nav class="sticky top-0 z-50 bg-white border-b border-gray-100">
  <div class="container px-4 py-2 mx-auto sm:px-6 lg:px-14">
    <div class="flex justify-between h-16">
      <div class="flex items-center">
        <a href="<?= site_url('/'); ?>" class="flex items-center gap-3">
          <img src="<?= public_path('assets/client/images/logo.png'); ?>" alt="Logo Lunar Store" width="45" />
          <span class="text-blue-600 font-bold text-[18px] lunar-text uppercase">Lunar Store</span>
        </a>
      </div>
      <div class="items-center hidden space-x-8 md:flex mobileMenu">
        <?php if (is_auth()): ?>
          <a href="<?= site_url('/catalog'); ?>" class="<?= is_active('/catalog'); ?> hover:text-blue-600 flex items-center">
            <i class="fas fa-box mr-1"></i>
            <span>Catalog</span>
          </a>
          <a href="<?= site_url('/cart'); ?>" class="<?= is_active('/cart'); ?> hover:text-blue-600 inline-flex items-center">
            <i class="fas fa-shopping-cart mr-1"></i>
            <span>Cart</span>
            <span class="rounded-md ms-2 bg-blue-100 py-1 px-1 text-xs font-semibold text-center text-blue-500 min-h-[24px] min-w-[24px]">
              5
            </span>
          </a>
          <a href="<?= site_url('/orders'); ?>" class="<?= is_active('/orders'); ?> hover:text-blue-600 flex items-center">
            <i class="fas fa-history mr-1"></i>
            <span>Orders</span>
          </a>

          <!-- User Profile Dropdown -->
          <div class="dropdown relative">
            <button class="flex items-center px-3 py-2 space-x-2 rounded-md hover:bg-gray-100" id="profileDropdown">
              <span class="text-gray-900 font-semibold"><?= $_SESSION['user_data']['username']; ?></span>
              <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                <img src="<?= asset('admin/static/images/faces/2.jpg'); ?>" alt="User" class="h-full w-full object-cover">
              </div>
              <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
            </button>
            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
              <a href="settings.php" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                <i class="fas fa-cog mr-2"></i>
                <span>Settings</span>
              </a>
              <a href="<?= site_url('/modules/client/auth.php?logout=true'); ?>" class="px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-600 flex items-center btnLogout">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span>Logout</span>
              </a>
            </div>
          </div>
        <?php else : ?>
          <a
            href="<?= site_url('/'); ?>"
            class="<?= is_active('/'); ?> transition-colors hover:text-blue-600">Home</a>
          <a
            href="<?= site_url('/aboutus'); ?>"
            class="<?= is_active('/aboutus'); ?> transition-colors hover:text-blue-600">About Us</a>
          <a
            href="#testimonials"
            class="text-gray-700 transition-colors hover:text-blue-600">Testimonials</a>
          <a
            href="#pricing"
            class="text-gray-700 transition-colors hover:text-blue-600">Pricing</a>
          <a
            href="#contact"
            class="text-gray-700 transition-colors hover:text-blue-600">Contact</a>
          <a
            href="<?= site_url('/login'); ?>"
            class="px-4 py-3 text-white transition-all bg-blue-500 rounded-md hover:bg-blue-600 active:scale-[0.9]">
            Login Now <i class="ml-1 fas fa-sign-in-alt"></i>
          </a>

        <?php endif; ?>
      </div>
      <div class="flex items-center md:hidden mobileNav">
        <button class="text-[24px] text-blue-500">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </div>
</nav>