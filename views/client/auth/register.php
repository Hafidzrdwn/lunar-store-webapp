<?php View::extend('client_auth'); ?>
<?php View::startSection('title'); ?>
Register
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<section class="flex flex-col md:flex-row h-screen">
  <div class="hidden lg:block w-full md:w-1/2 xl:w-2/3 h-screen fixed left-0 top-0">
    <img
      src="<?= asset('client/images/bg_login_lunar2.png'); ?>"
      alt="Auth Banner"
      class="w-full h-full object-cover" />
  </div>

  <div class="bg-white w-full md:max-w-md lg:max-w-full md:w-1/2 xl:w-1/3 min-h-screen overflow-y-auto px-6 lg:px-16 xl:px-12 lg:ml-auto">
    <div class="w-full max-w-md py-12 mx-auto">
      <h1 class="text-xl md:text-2xl font-bold leading-tight mb-3">
        Register New Account
      </h1>
      <a
        href="<?= site_url('/'); ?>"
        class="flex items-center text-blue-500 hover:text-blue-700 w-max transition-colors">
        <span>&laquo; Back To Home</span>
      </a>

      <?php if (isset($_SESSION['errors']['register'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
          <span class="block sm:inline"><?= $_SESSION['errors']['register'][0]; ?></span>
        </div>
      <?php endif; ?>

      <form class="mt-6" action="<?= site_url('/modules/client/auth.php'); ?>" method="POST">
        <!-- Username field -->
        <div class="mb-4">
          <label class="block text-gray-700" for="username">Username</label>
          <input
            type="text"
            name="username"
            id="username"
            placeholder="Enter Username"
            class="w-full px-4 py-3 rounded-md mt-2 border <?= isset($_SESSION['errors']['username']) ? 'border-red-500' : 'focus:border-blue-500'; ?> focus:bg-white focus:outline-none"
            value="<?= $_SESSION['old']['username'] ?? ''; ?>"
            minlength="4"
            autofocus />
          <?php if (isset($_SESSION['errors']['username'])): ?>
            <p class="text-red-500 text-xs italic mt-1"><?= $_SESSION['errors']['username'][0]; ?></p>
          <?php endif; ?>
        </div>

        <!-- Fullname field -->
        <div class="mb-4">
          <label class="block text-gray-700" for="fullname">Fullname</label>
          <input
            type="text"
            name="fullname"
            id="fullname"
            placeholder="Enter Fullname"
            class="w-full px-4 py-3 rounded-md mt-2 border <?= isset($_SESSION['errors']['fullname']) ? 'border-red-500' : 'focus:border-blue-500'; ?> focus:bg-white focus:outline-none"
            value="<?= $_SESSION['old']['fullname'] ?? ''; ?>"
            minlength="6" />
          <?php if (isset($_SESSION['errors']['fullname'])): ?>
            <p class="text-red-500 text-xs italic mt-1"><?= $_SESSION['errors']['fullname'][0]; ?></p>
          <?php endif; ?>
        </div>

        <!-- Email field -->
        <div class="mb-4">
          <label class="block text-gray-700" for="email">Email Address</label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter Email Address"
            class="w-full px-4 py-3 rounded-md mt-2 border <?= isset($_SESSION['errors']['email']) ? 'border-red-500' : 'focus:border-blue-500'; ?> focus:bg-white focus:outline-none"
            value="<?= $_SESSION['old']['email'] ?? ''; ?>" />
          <?php if (isset($_SESSION['errors']['email'])): ?>
            <p class="text-red-500 text-xs italic mt-1"><?= $_SESSION['errors']['email'][0]; ?></p>
          <?php endif; ?>
        </div>

        <!-- Password field -->
        <div class="mb-4">
          <label class="block text-gray-700" for="password">Password</label>
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter Password"
            minlength="6"
            class="w-full px-4 py-3 rounded-md mt-2 border <?= isset($_SESSION['errors']['password']) ? 'border-red-500' : 'focus:border-blue-500'; ?> focus:bg-white focus:outline-none" />
          <?php if (isset($_SESSION['errors']['password'])): ?>
            <p class="text-red-500 text-xs italic mt-1"><?= $_SESSION['errors']['password'][0]; ?></p>
          <?php endif; ?>
        </div>

        <!-- Confirm Password field -->
        <div class="mb-4">
          <label class="block text-gray-700" for="confirm_password">Confirm Password</label>
          <input
            type="password"
            name="confirm_password"
            id="confirm_password"
            placeholder="Confirm Password"
            minlength="6"
            class="w-full px-4 py-3 rounded-md mt-2 border <?= isset($_SESSION['errors']['confirm_password']) ? 'border-red-500' : 'focus:border-blue-500'; ?> focus:bg-white focus:outline-none" />
          <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
            <p class="text-red-500 text-xs italic mt-1"><?= $_SESSION['errors']['confirm_password'][0]; ?></p>
          <?php endif; ?>
        </div>

        <!-- Terms and conditions checkbox -->
        <div class="flex items-start mb-4 w-max">
          <div class="flex items-center h-5">
            <input
              type="checkbox"
              id="terms"
              name="terms"
              class="h-4 w-4 cursor-pointer text-blue-500 border-gray-300 rounded focus:ring-blue-500 <?= isset($_SESSION['errors']['terms']) ? 'border-red-500' : ''; ?>" />
          </div>
          <div class="ml-3 text-sm">
            <label for="terms" class="text-gray-700 cursor-pointer">
              I accept the
              <a href="<?= site_url('/terms'); ?>" class="text-blue-500 hover:underline">
                Terms and Conditions
              </a>
            </label>
            <?php if (isset($_SESSION['errors']['terms'])): ?>
              <p class="text-red-500 text-xs italic mt-1"><?= $_SESSION['errors']['terms'][0]; ?></p>
            <?php endif; ?>
          </div>
        </div>

        <button
          type="submit" name="register"
          class="w-full block bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 mt-6 text-center">
          Register Now
        </button>
      </form>

      <hr class="my-4 border-gray-300 w-full" />

      <button
        type="button"
        class="w-full block bg-white hover:bg-gray-100 focus:bg-gray-100 text-gray-900 font-semibold rounded-lg px-4 py-3 border border-gray-300">
        <div class="flex items-center justify-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            class="w-6 h-6"
            viewBox="0 0 48 48">
            <defs>
              <path
                id="a"
                d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z" />
            </defs>
            <clipPath id="b">
              <use xlink:href="#a" overflow="visible" />
            </clipPath>
            <path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z" />
            <path
              clip-path="url(#b)"
              fill="#EA4335"
              d="M0 11l17 13 7-6.1L48 14V0H0z" />
            <path
              clip-path="url(#b)"
              fill="#34A853"
              d="M0 37l30-23 7.9 1L48 0v48H0z" />
            <path
              clip-path="url(#b)"
              fill="#4285F4"
              d="M48 48L17 24l-4-3 35-10z" />
          </svg>
          <span class="ml-4">Sign up with Google</span>
        </div>
      </button>

      <p class="mt-5 text-center">
        Already have an account?
        <a
          href="<?= site_url('/login'); ?>"
          class="text-blue-500 hover:text-blue-700 font-semibold underline underline-offset-2">Login Now</a>
      </p>
    </div>
  </div>
</section>
<?php
unset($_SESSION['old']);
unset($_SESSION['errors']);
View::endSection(); ?>

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
<?php View::endSection(); ?>