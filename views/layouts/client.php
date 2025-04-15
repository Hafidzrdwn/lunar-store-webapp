<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lunar Store - <?php View::yield('title'); ?></title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&family=Righteous&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= asset('client/css/style.css') ?>" />
  <link rel="stylesheet" href="<?= asset('client/css/custom.css') ?>" />

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <?php View::yield('custom_css'); ?>
</head>

<body class="min-h-screen bg-white">
  <!-- Navigation -->
  <?php component('client/navbar'); ?>

  <!-- Main Content -->
  <?php View::yield('content'); ?>

  <!-- Footer -->
  <?php component('client/footer'); ?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const mobileMenuButton = document.querySelector(".mobileNav button");
      const mobileMenuButtonIcon = document.querySelector(".mobileNav button > i");
      const mobileMenu = document.querySelector(
        ".mobileMenu"
      );

      if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", function() {
          if (mobileMenu.classList.contains("hidden")) {
            mobileMenuButtonIcon.classList.remove("fa-bars");
            mobileMenuButtonIcon.classList.add("fa-times");
            mobileMenu.classList.remove("hidden", "space-x-8");
            mobileMenu.classList.add(
              "flex",
              "flex-col",
              "absolute",
              "top-16",
              "left-0",
              "right-0",
              "bg-white",
              "px-4",
              "py-6",
              "shadow-md",
              "z-50",
              "space-y-6"
            );
          } else {
            mobileMenuButtonIcon.classList.remove("fa-times");
            mobileMenuButtonIcon.classList.add("fa-bars");
            mobileMenu.classList.add("hidden");
            mobileMenu.classList.remove(
              "flex",
              "flex-col",
              "absolute",
              "top-16",
              "left-0",
              "right-0",
              "bg-white",
              "p-4",
              "shadow-md",
              "z-50",
              "space-y-4"
            );
          }
        });
      }
      // Toggle dropdown menu
      const profileDropdown = document.getElementById('profileDropdown');
      const dropdown = document.querySelector('.dropdown');

      profileDropdown.addEventListener('click', function() {
        dropdown.classList.toggle('active');
      });

      // Close dropdown when clicking outside
      window.addEventListener('click', function(event) {
        if (!dropdown.contains(event.target)) {
          dropdown.classList.remove('active');
        }
      });
    });
  </script>
  <?php View::yield('custom_js'); ?>
</body>

</html>