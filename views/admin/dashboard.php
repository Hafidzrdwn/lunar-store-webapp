<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Dashboard
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<?php require_once BASE_PATH . '/modules/admin/dashboard.php' ?>
<header class="mb-3">
  <a href="#" class="burger-btn d-block d-xl-none">
    <i class="bi bi-justify fs-3"></i>
  </a>
</header>

<div class="page-heading">
  <h3>Lunar Store Dashboard</h3>
  <p class="text-subtitle text-muted">Digital products and game top-up analytics</p>
</div>

<div class="page-content">
  <section class="row">
    <div class="col-12 col-lg-12">
      <!-- Stats Cards Row -->
      <div class="row">
        <!-- Total Users Card -->
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                  <div class="stats-icon purple mb-2">
                    <i class="bi bi-people-fill"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Total Users</h6>
                  <h6 class="font-extrabold mb-0"><?= number_format($total_users); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                  <div class="stats-icon blue mb-2">
                    <i class="bi bi-box-seam"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Total Apps</h6>
                  <h6 class="font-extrabold mb-0"><?= number_format($total_products); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Categories Card -->
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                  <div class="stats-icon green mb-2">
                    <i class="bi bi-tags-fill"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Categories</h6>
                  <h6 class="font-extrabold mb-0"><?= number_format($total_categories); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-6 col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body px-4 py-4">
              <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                  <div class="stats-icon red mb-2">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                  <h6 class="text-muted font-semibold">Est. Revenue</h6>
                  <h6 class="font-extrabold mb-0">Rp <?= number_format($total_revenue, 0, ',', '.'); ?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Product Types & Best Products Distribution -->
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h4>Product Types</h4>
            </div>
            <div class="card-body">
              <canvas id="product-types-chart" style="min-height: 250px;"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h4>Best-Seller Products</h4>
            </div>
            <div class="card-body">
              <canvas id="best-products-chart" style="min-height: 250px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Sales Trend Chart -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>Sales Overview</h4>
              <div class="card-header-action">
                <div class="btn-group">
                  <button type="button" class="btn btn-outline-primary active" data-chart-type="sales">Sales</button>
                  <button type="button" class="btn btn-outline-primary" data-chart-type="users">Users</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <canvas id="sales-trend-chart" style="min-height: 365px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Product Categories and Top-up Games -->
      <div class="row">
        <!-- Product Categories -->
        <div class="col-12 col-xl-8">
          <div class="card">
            <div class="card-header">
              <h4>Popular Categories</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-lg">
                  <thead>
                    <tr>
                      <th>Category</th>
                      <th>Products</th>
                      <th>Popularity</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($popular_categories as $category): ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-3">
                              <?php if ($category['image']): ?>
                                <img src="<?= asset('uploads/categories/' . $category['image']); ?>" alt="<?= $category['title']; ?>">
                              <?php else: ?>
                                <div class="avatar-content"><?= substr($category['title'], 0, 1); ?></div>
                              <?php endif; ?>
                            </div>
                            <p class="font-bold mb-0"><?= $category['title']; ?></p>
                          </div>
                        </td>
                        <td><?= number_format($category['product_count']); ?></td>
                        <td>
                          <div class="progress" style="height: 7px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $category['percentage']; ?>%"
                              aria-valuenow="<?= $category['percentage']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Top-up Games -->
        <div class="col-12 col-xl-4">
          <div class="card">
            <div class="card-header">
              <h4>Top-up Games</h4>
            </div>
            <div class="card-body">
              <canvas id="topup-games-chart" style="min-height: 300px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Latest Products -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Latest Products</h4>
              <div class="card-header-action">
                <a href="<?= site_url('/admin/products'); ?>" class="btn btn-primary">View All</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>App Name</th>
                      <th>Category</th>
                      <th>Price Range</th>
                      <th>Added</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($latest_products)): ?>
                      <tr>
                        <td colspan="5" class="text-center">No products found</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($latest_products as $product): ?>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar avatar-md me-3">
                                <?php if ($product['cover_img']): ?>
                                  <img src="<?= asset('uploads/products/' . $product['cover_img']); ?>" alt="<?= $product['app_name']; ?>">
                                <?php else: ?>
                                  <div class="avatar-content"><?= substr($product['app_name'], 0, 1); ?></div>
                                <?php endif; ?>
                              </div>
                              <div>
                                <p class="font-bold mb-0"><?= $product['app_name']; ?></p>
                                <p class="text-muted mb-0 small"><?= substr($product['description'], 0, 30); ?>...</p>
                              </div>
                            </div>
                          </td>
                          <td><?= $product['category_title']; ?></td>
                          <td>Rp <?= number_format($product['min_price'], 0, ',', '.'); ?> - Rp <?= number_format($product['max_price'], 0, ',', '.'); ?></td>
                          <td><?= time_elapsed_string($product['created_at']); ?></td>
                          <td>
                            <a href="<?= site_url('/admin/products/edit/' . $product['id']); ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                            <a href="<?= site_url('/admin/products/delete/' . $product['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"><i class="bi bi-trash"></i></a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<script>
  $(document).ready(function() {
    // Show success message if exists
    <?php if (isset($_SESSION['success'])): ?>
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= $_SESSION['success']; ?>',
        confirmButtonColor: '#435ebe'
      });
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    // Sales Trend Chart
    const salesTrendCtx = $('#sales-trend-chart').get(0).getContext('2d');
    const salesTrendChart = new Chart(salesTrendCtx, {
      type: 'line',
      data: {
        labels: <?= json_encode($sales_trend['labels']); ?>,
        datasets: [{
          label: 'Estimated Sales',
          data: <?= json_encode($sales_trend['data']); ?>,
          fill: true,
          backgroundColor: 'rgba(67, 94, 190, 0.2)',
          borderColor: 'rgba(67, 94, 190, 1)',
          tension: 0.4,
          pointBackgroundColor: 'rgba(67, 94, 190, 1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba(67, 94, 190, 1)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              display: true,
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.7)',
            padding: 10,
            titleColor: '#fff',
            titleFont: {
              size: 14
            },
            bodyColor: '#fff',
            bodyFont: {
              size: 14
            }
          }
        }
      }
    });

    // Top-up Games Chart (Pie Chart)
    const topupGamesCtx = $('#topup-games-chart').get(0).getContext('2d');
    const topupGamesChart = new Chart(topupGamesCtx, {
      type: 'pie',
      data: {
        labels: <?= json_encode(array_column($topup_games, 'diamond')); ?>,
        datasets: [{
          data: <?= json_encode(array_column($topup_games, 'price')); ?>,
          backgroundColor: [
            'rgba(67, 94, 190, 0.8)',
            'rgba(46, 204, 113, 0.8)',
            'rgba(231, 76, 60, 0.8)',
            'rgba(241, 196, 15, 0.8)',
            'rgba(155, 89, 182, 0.8)',
            'rgba(52, 152, 219, 0.8)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              formatter: (value) => 'Diamond: ' + value
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Diamond: ' + context.label + ' - Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
              }
            }
          }
        }
      }
    });

    // Product Types Chart (Doughnut Chart)
    const productTypesCtx = $('#product-types-chart').get(0).getContext('2d');
    const productTypesChart = new Chart(productTypesCtx, {
      type: 'doughnut',
      data: {
        labels: <?= json_encode(array_column($product_types, 'type_name')); ?>,
        datasets: [{
          data: <?= json_encode(array_column($product_types, 'count')); ?>,
          backgroundColor: [
            'rgba(67, 94, 190, 0.8)',
            'rgba(46, 204, 113, 0.8)',
            'rgba(231, 76, 60, 0.8)',
            'rgba(241, 196, 15, 0.8)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });

    // Toggle between sales and users chart
    $('[data-chart-type]').on('click', function() {
      const chartType = $(this).data('chart-type');

      // Update active state
      $('[data-chart-type]').removeClass('active');
      $(this).addClass('active');

      // AJAX request to get new data
      $.ajax({
        url: '<?= site_url('/admin/dashboard/get-chart-data'); ?>',
        type: 'GET',
        data: {
          type: chartType
        },
        dataType: 'json',
        success: function(response) {
          // Update chart data
          salesTrendChart.data.labels = response.labels;
          salesTrendChart.data.datasets[0].label = chartType === 'sales' ? 'Estimated Sales' : 'New Users';
          salesTrendChart.data.datasets[0].data = response.data;
          salesTrendChart.update();
        },
        error: function(xhr, status, error) {
          console.error('Error fetching chart data:', error);
        }
      });
    });
  });
</script>
<?php View::endSection(); ?>