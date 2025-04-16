<header>
  <nav class="navbar navbar-expand navbar-light navbar-top">
    <div class="container-fluid">
      <a href="#" class="burger-btn d-block">
        <i class="bi bi-justify fs-3"></i>
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="dropdown ms-auto">
          <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="user-menu d-flex">
              <div class="user-name text-end me-3">
                <h6 class="mb-0 text-gray-600"><?= $_SESSION['admin_fullname']; ?></h6>
                <p class="mb-0 text-sm text-gray-600">Administrator</p>
              </div>
              <div class="user-img d-flex align-items-center">
                <div class="avatar avatar-md">
                  <img src="<?= asset('admin/compiled/jpg/1.jpg') ?>" />
                </div>
              </div>
            </div>
          </a>
          <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="dropdownMenuButton"
            style="min-width: 11rem">
            <li>
              <h6 class="dropdown-header">Hello, <?= $_SESSION['admin_username']; ?>!</h6>
            </li>
            <li>
              <a class="dropdown-item btnLogout" href="<?= site_url('/modules/admin/auth.php?logout=true'); ?>"><i class="fas fa-sign-out-alt me-2"></i>
                Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>