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
      </div>
      <div class="flex items-center md:hidden mobileNav">
        <button class="text-[24px] text-blue-500">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </div>
</nav>