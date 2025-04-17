<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Data Detail Produk
<?php View::endSection(); ?>

<?php View::startSection('custom_css'); ?>
<style>
  .editable:hover {
    background-color: #f8f9fa;
    cursor: pointer;
  }

  .edit-input {
    width: 100%;
    padding: 5px;
    border: 1px solid #ced4da;
    border-radius: 4px;
  }

  .dt-buttons {
    margin-bottom: 15px;
  }

  .detail-action-btn {
    margin: 0 3px;
  }

  #detailTable_filter {
    margin-bottom: 15px;
  }

  .select-info {
    margin-left: 10px;
  }

  .loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .notes-cell {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .price-row {
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    background-color: #f8f9fa;
  }

  .remove-price-btn {
    margin-top: 32px;
  }

  .add-price-btn {
    margin-bottom: 20px;
  }

  /* New styles for grouped rows */
  .group-header-row {
    background-color: #e9ecef;
    font-weight: bold;
  }

  .group-header-cell {
    padding: 10px 15px !important;
    font-size: 1.1em;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
  }

  .group-subheader-row {
    background-color: #f8f9fa;
  }

  .group-subheader-cell {
    padding: 8px 15px !important;
    font-weight: 600;
    color: #6c757d;
    border-bottom: 1px solid #dee2e6;
    padding-left: 30px !important;
  }

  .hidden-cell {
    display: none;
  }

  /* Zebra striping for rows */
  #detailTable tbody tr:nth-of-type(odd):not(.group-header-row):not(.group-subheader-row) {
    background-color: rgba(0, 0, 0, 0.02);
  }

  #detailTable tbody tr:nth-of-type(even):not(.group-header-row):not(.group-subheader-row) {
    background-color: #ffffff;
  }

  #detailTable tbody tr:hover:not(.group-header-row):not(.group-subheader-row) {
    background-color: rgba(0, 0, 0, 0.05);
  }
</style>
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Data Detail Produk</h3>
        <p class="text-subtitle text-muted">Kelola detail produk untuk toko Anda</p>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Detail Produk</h4>
        <button type="button" class="btn btn-primary" id="btnAddDetail">
          <i class="fas fa-plus-circle"></i> Tambah Detail Produk
        </button>
      </div>
      <div class="card-body position-relative">
        <div class="table-responsive">
          <table class="table table-striped" id="detailTable">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>#</th>
                <th>Nama Aplikasi</th>
                <th>Tipe</th>
                <th>Durasi</th>
                <th>Harga</th>
                <th>Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be loaded via AJAX -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Add Detail Modal -->
<div class="modal fade" id="addDetailModal" tabindex="-1" aria-labelledby="addDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDetailModalLabel">Tambah Detail Produk Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addDetailForm">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="product_id" class="form-label">Produk</label>
              <select class="form-select" id="product_id" name="product_id" required>
                <option value="">Pilih Produk</option>
                <!-- Products will be loaded via AJAX -->
              </select>
              <div class="invalid-feedback" id="product_idError"></div>
            </div>
            <div class="col-md-6">
              <label for="product_type_id" class="form-label">Tipe Produk</label>
              <select class="form-select" id="product_type_id" name="product_type_id" required>
                <option value="">Pilih Tipe</option>
                <!-- Product types will be loaded via AJAX -->
              </select>
              <div class="invalid-feedback" id="product_type_idError"></div>
            </div>
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label">Catatan</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            <div class="invalid-feedback" id="notesError"></div>
          </div>

          <hr>

          <h5 class="mb-3">Harga dan Durasi</h5>

          <div id="priceContainer">
            <div class="price-row row">
              <div class="col-md-3">
                <label for="duration_0" class="form-label">Durasi</label>
                <input type="number" class="form-control" id="duration_0" name="duration[]" min="1">
              </div>
              <div class="col-md-3">
                <label for="duration_unit_0" class="form-label">Satuan</label>
                <select class="form-select" id="duration_unit_0" name="duration_unit[]">
                  <option value="Day">Hari</option>
                  <option value="Days">Hari</option>
                  <option value="Week">Minggu</option>
                  <option value="Weeks">Minggu</option>
                  <option value="Month">Bulan</option>
                  <option value="Months">Bulan</option>
                  <option value="Year">Tahun</option>
                  <option value="Years">Tahun</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="price_0" class="form-label">Harga</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" class="form-control" id="price_0" name="price[]" required min="0">
                </div>
                <div class="invalid-feedback" id="priceError_0"></div>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-price-btn" disabled>
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="text-center mt-3">
            <button type="button" class="btn btn-success add-price-btn">
              <i class="fas fa-plus-circle"></i> Tambah Harga
            </button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSaveDetail">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Detail Modal -->
<div class="modal fade" id="editDetailModal" tabindex="-1" aria-labelledby="editDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDetailModalLabel">Edit Detail Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editDetailForm">
          <input type="hidden" id="edit_detail_id" name="detailId">

          <div class="mb-3">
            <label for="edit_product_id" class="form-label">Produk</label>
            <select class="form-select" id="edit_product_id" name="product_id" required>
              <option value="">Pilih Produk</option>
              <!-- Products will be loaded via AJAX -->
            </select>
            <div class="invalid-feedback" id="edit_product_idError"></div>
          </div>

          <div class="mb-3">
            <label for="edit_product_type_id" class="form-label">Tipe Produk</label>
            <select class="form-select" id="edit_product_type_id" name="product_type_id" required>
              <option value="">Pilih Tipe</option>
              <!-- Product types will be loaded via AJAX -->
            </select>
            <div class="invalid-feedback" id="edit_product_type_idError"></div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_duration" class="form-label">Durasi</label>
              <input type="number" class="form-control" id="edit_duration" name="duration" min="1">
              <div class="invalid-feedback" id="edit_durationError"></div>
            </div>
            <div class="col-md-6">
              <label for="edit_duration_unit" class="form-label">Satuan</label>
              <select class="form-select" id="edit_duration_unit" name="duration_unit">
                <option value="Day">Day</option>
                <option value="Days">Days</option>
                <option value="Week">Week</option>
                <option value="Weeks">Weeks</option>
                <option value="Month">Month</option>
                <option value="Months">Months</option>
                <option value="Year">Year</option>
                <option value="Years">Years</option>
              </select>
              <div class="invalid-feedback" id="edit_duration_unitError"></div>
            </div>
          </div>

          <div class="mb-3">
            <label for="edit_price" class="form-label">Harga</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" class="form-control" id="edit_price" name="price" required min="0">
            </div>
            <div class="invalid-feedback" id="edit_priceError"></div>
          </div>

          <div class="mb-3">
            <label for="edit_notes" class="form-label">Catatan</label>
            <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
            <div class="invalid-feedback" id="edit_notesError"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnUpdateDetail">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus detail produk yang dipilih?</p>
        <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Hapus</button>
      </div>
    </div>
  </div>
</div>
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<script>
  $(document).ready(function() {
    // Load products and product types for select boxes
    loadProducts();
    loadProductTypes();

    // Initialize DataTable
    const detailTable = $('#detailTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'POST',
        data: function(d) {
          d.get_product_details = true;
          return d;
        }
      },
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
          className: 'dt-center',
          render: function() {
            return '<div class="form-check"><input class="form-check-input detail-select" type="checkbox"></div>';
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: 'dt-center',
          render: function(data, type, row, meta) {
            return meta.row + 1 + meta.settings._iDisplayStart;
          }
        },
        {
          data: 'product_name',
          className: 'group-header',
          render: function(data, type, row) {
            if (type === 'display') {
              return row.show_product ? `<div class="product-cell">${data}</div>` : '';
            }
            return data;
          }
        },
        {
          data: 'type_name',
          className: 'group-item',
          render: function(data, type, row) {
            if (type === 'display') {
              return row.show_type ? `<div class="type-cell">${data}</div>` : '';
            }
            return data;
          }
        },
        {
          data: 'duration',
          render: function(data, type, row) {
            if (type === 'display') {
              return data || '-';
            }
            return data;
          }
        },
        {
          data: 'price_formatted'
        },
        {
          data: 'notes',
          className: 'notes-cell',
          render: function(data, type, row) {
            if (type === 'display') {
              return data || '-';
            }
            return data;
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            return `
              <div class="d-flex">
                <button type="button" class="btn btn-sm btn-primary detail-action-btn btn-edit" data-id="${row.id}">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger detail-action-btn btn-delete" data-id="${row.id}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            `;
          }
        }
      ],
      order: [
        [2, 'asc'],
        [3, 'asc']
      ],
      dom: 'Bfrtip',
      buttons: [{
        text: '<i class="fas fa-trash"></i> Hapus Terpilih',
        className: 'btn btn-danger',
        action: function() {
          const selectedIds = getSelectedDetailIds();
          if (selectedIds.length > 0) {
            showDeleteModal(selectedIds);
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Silakan pilih detail produk yang akan dihapus terlebih dahulu!'
            });
          }
        }
      }],
      drawCallback: function(settings) {
        // Group rows by product and type
        let api = this.api();
        let rows = api.rows({
          page: 'current'
        }).nodes();
        let last = null;
        let lastType = null;

        // Group by product
        api.column(2, {
          page: 'current'
        }).data().each(function(group, i) {
          let row = api.row(i).data();
          if (last !== group && row.show_product) {
            $(rows).eq(i).before(
              `<tr class="group-header-row"><td colspan="8" class="group-header-cell">${group}</td></tr>`
            );
            last = group;
            lastType = null; // Reset type grouping when product changes
          }

          // Subgroup by type
          let type = api.column(3, {
            page: 'current'
          }).data()[i];
          if ((lastType !== type || last !== group) && row.show_type) {
            $(rows).eq(i).before(
              `<tr class="group-subheader-row"><td></td><td colspan="7" class="group-subheader-cell">${type}</td></tr>`
            );
            lastType = type;
          }

          // Hide product and type cells since we're showing them in group headers
          if (row.show_product) {
            $(rows).eq(i).find('td:eq(2)').addClass('hidden-cell');
          } else {
            $(rows).eq(i).find('td:eq(2)').addClass('hidden-cell');
          }

          if (row.show_type) {
            $(rows).eq(i).find('td:eq(3)').addClass('hidden-cell');
          } else {
            $(rows).eq(i).find('td:eq(3)').addClass('hidden-cell');
          }
        });
      }
    });

    // Select All Checkbox
    $('#selectAll').on('click', function() {
      $('.detail-select').prop('checked', this.checked);
    });

    // Check if all checkboxes are selected
    $('#detailTable tbody').on('change', '.detail-select', function() {
      const allChecked = $('.detail-select:checked').length === $('.detail-select').length;
      $('#selectAll').prop('checked', allChecked);
    });

    // Get selected detail IDs
    function getSelectedDetailIds() {
      const selectedIds = [];
      $('.detail-select:checked').each(function() {
        const rowData = detailTable.row($(this).closest('tr')).data();
        selectedIds.push(rowData.id);
      });
      return selectedIds;
    }

    // Load products for select box
    function loadProducts() {
      $.ajax({
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'GET',
        data: {
          get_products: true
        },
        success: function(response) {
          if (response.success) {
            const products = response.data;
            let options = '<option value="">Pilih Produk</option>';

            products.forEach(function(product) {
              options += `<option value="${product.id}">${product.app_name}</option>`;
            });

            $('#product_id, #edit_product_id').html(options);
          }
        },
        error: function() {
          console.error('Failed to load products');
        }
      });
    }

    // Load product types for select box
    function loadProductTypes() {
      $.ajax({
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'GET',
        data: {
          get_product_types: true
        },
        success: function(response) {
          if (response.success) {
            const types = response.data;
            let options = '<option value="">Pilih Tipe</option>';

            types.forEach(function(type) {
              options += `<option value="${type.id}">${type.type_name}</option>`;
            });

            $('#product_type_id, #edit_product_type_id').html(options);
          }
        },
        error: function() {
          console.error('Failed to load product types');
        }
      });
    }

    // Dynamic price rows
    let priceRowCount = 1;

    // Add price row
    $('.add-price-btn').on('click', function() {
      const newRow = `
        <div class="price-row row">
          <div class="col-md-3">
            <label for="duration_${priceRowCount}" class="form-label">Durasi</label>
            <input type="number" class="form-control" id="duration_${priceRowCount}" name="duration[]" min="1">
          </div>
          <div class="col-md-3">
            <label for="duration_unit_${priceRowCount}" class="form-label">Satuan</label>
            <select class="form-select" id="duration_unit_${priceRowCount}" name="duration_unit[]">
              <option value="Day">Day</option>
              <option value="Days">Days</option>
              <option value="Week">Week</option>
              <option value="Weeks">Weeks</option>
              <option value="Month">Month</option>
              <option value="Months">Months</option>
              <option value="Year">Year</option>
              <option value="Years">Years</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="price_${priceRowCount}" class="form-label">Harga</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" class="form-control" id="price_${priceRowCount}" name="price[]" required min="0">
            </div>
            <div class="invalid-feedback" id="priceError_${priceRowCount}"></div>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-price-btn">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      `;

      $('#priceContainer').append(newRow);
      priceRowCount++;

      // Enable the first remove button if there are more than one price rows
      if ($('.price-row').length > 1) {
        $('.remove-price-btn').prop('disabled', false);
      }
    });

    // Remove price row
    $(document).on('click', '.remove-price-btn', function() {
      $(this).closest('.price-row').remove();

      // Disable the last remove button if there's only one price row left
      if ($('.price-row').length === 1) {
        $('.remove-price-btn').prop('disabled', true);
      }
    });

    // Show Add Detail Modal
    $('#btnAddDetail').on('click', function() {
      resetAddDetailForm();
      $('#addDetailModal').modal('show');
    });

    // Show Edit Detail Modal
    $('#detailTable').on('click', '.btn-edit', function() {
      const detailId = $(this).data('id');
      resetEditDetailForm();

      // Show loading overlay
      showLoading();

      // Fetch detail data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'GET',
        data: {
          detailId: detailId
        },
        success: function(response) {
          if (response.success) {
            const detail = response.data;
            $('#edit_detail_id').val(detail.id);
            $('#edit_product_id').val(detail.product_id);
            $('#edit_product_type_id').val(detail.product_type_id);
            $('#edit_duration').val(detail.duration);
            $('#edit_duration_unit').val(detail.duration_unit);
            $('#edit_price').val(detail.price);
            $('#edit_notes').val(detail.notes);

            $('#editDetailModal').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Terjadi kesalahan saat mengambil data detail'
            });
          }
          hideLoading();
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data detail'
          });
          hideLoading();
        }
      });
    });

    // Save Detail
    $('#btnSaveDetail').on('click', function() {
      // Reset validation errors
      resetValidationErrors();

      // Validate required fields
      if (!validateAddDetailForm()) {
        return;
      }

      // Show loading overlay
      showLoading();

      // Submit form data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'POST',
        data: $('#addDetailForm').serialize() + '&saveDetail=true',
        success: function(response) {
          if (response.success) {
            $('#addDetailModal').modal('hide');
            detailTable.ajax.reload();
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message
            });
          } else {
            if (response.errors) {
              displayValidationErrors(response.errors);
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Terjadi kesalahan saat menyimpan data'
              });
            }
          }
          hideLoading();
        },
        error: function(xhr) {
          if (xhr.status === 422 && xhr.responseJSON) {
            displayValidationErrors(xhr.responseJSON.errors);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Terjadi kesalahan saat menyimpan data'
            });
          }
          hideLoading();
        }
      });
    });

    // Update Detail
    $('#btnUpdateDetail').on('click', function() {
      const detailId = $('#edit_detail_id').val();

      // Reset validation errors
      resetEditValidationErrors();

      // Validate required fields
      if (!validateEditDetailForm()) {
        return;
      }

      // Show loading overlay
      showLoading();

      // Submit form data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'POST',
        data: $('#editDetailForm').serialize() + '&editDetail=true&detailId=' + detailId,
        success: function(response) {
          if (response.success) {
            $('#editDetailModal').modal('hide');
            detailTable.ajax.reload();
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message
            });
          } else {
            if (response.errors) {
              displayEditValidationErrors(response.errors);
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Terjadi kesalahan saat memperbarui data'
              });
            }
          }
          hideLoading();
        },
        error: function(xhr) {
          if (xhr.status === 422 && xhr.responseJSON) {
            displayEditValidationErrors(xhr.responseJSON.errors);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Terjadi kesalahan saat memperbarui data'
            });
          }
          hideLoading();
        }
      });
    });

    // Show Delete Confirmation Modal
    $('#detailTable').on('click', '.btn-delete', function() {
      const detailId = $(this).data('id');
      showDeleteModal([detailId]);
    });

    // Confirm Delete
    $('#btnConfirmDelete').on('click', function() {
      const selectedIds = $(this).data('ids');

      // Show loading overlay
      showLoading();

      $.ajax({
        url: '<?= site_url('/modules/admin/product_details.php') ?>',
        type: 'POST',
        data: {
          ids: selectedIds,
          delete_detail: true
        },
        success: function(response) {
          if (response.success) {
            $('#deleteModal').modal('hide');
            detailTable.ajax.reload();
            $('#selectAll').prop('checked', false);
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Terjadi kesalahan saat menghapus data'
            });
          }
          hideLoading();
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat menghapus data'
          });
          hideLoading();
        }
      });
    });

    // Helper Functions
    function resetAddDetailForm() {
      $('#addDetailForm')[0].reset();

      // Reset price rows
      $('#priceContainer').html(`
        <div class="price-row row">
          <div class="col-md-3">
            <label for="duration_0" class="form-label">Durasi</label>
            <input type="number" class="form-control" id="duration_0" name="duration[]" min="1">
          </div>
          <div class="col-md-3">
            <label for="duration_unit_0" class="form-label">Satuan</label>
            <select class="form-select" id="duration_unit_0" name="duration_unit[]">
              <option value="Day">Hari</option>
              <option value="Days">Hari</option>
              <option value="Week">Minggu</option>
              <option value="Weeks">Minggu</option>
              <option value="Month">Bulan</option>
              <option value="Months">Bulan</option>
              <option value="Year">Tahun</option>
              <option value="Years">Tahun</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="price_0" class="form-label">Harga</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" class="form-control" id="price_0" name="price[]" required min="0">
            </div>
            <div class="invalid-feedback" id="priceError_0"></div>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-price-btn" disabled>
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      `);

      priceRowCount = 1;
      resetValidationErrors();
    }

    function resetEditDetailForm() {
      $('#editDetailForm')[0].reset();
      resetEditValidationErrors();
    }

    function resetValidationErrors() {
      $('#addDetailForm .is-invalid').removeClass('is-invalid');
      $('#addDetailForm .invalid-feedback').text('');
    }

    function resetEditValidationErrors() {
      $('#editDetailForm .is-invalid').removeClass('is-invalid');
      $('#editDetailForm .invalid-feedback').text('');
    }

    function validateAddDetailForm() {
      let isValid = true;

      // Validate product
      if (!$('#product_id').val()) {
        $('#product_id').addClass('is-invalid');
        $('#product_idError').text('Produk harus dipilih');
        isValid = false;
      }

      // Validate product type
      if (!$('#product_type_id').val()) {
        $('#product_type_id').addClass('is-invalid');
        $('#product_type_idError').text('Tipe produk harus dipilih');
        isValid = false;
      }

      // Validate at least one price
      let hasValidPrice = false;
      $('input[name="price[]"]').each(function(index) {
        if ($(this).val()) {
          hasValidPrice = true;
        }
      });

      if (!hasValidPrice) {
        $('#price_0').addClass('is-invalid');
        $('#priceError_0').text('Minimal satu harga harus diisi');
        isValid = false;
      }

      return isValid;
    }

    function validateEditDetailForm() {
      let isValid = true;

      // Validate product
      if (!$('#edit_product_id').val()) {
        $('#edit_product_id').addClass('is-invalid');
        $('#edit_product_idError').text('Produk harus dipilih');
        isValid = false;
      }

      // Validate product type
      if (!$('#edit_product_type_id').val()) {
        $('#edit_product_type_id').addClass('is-invalid');
        $('#edit_product_type_idError').text('Tipe produk harus dipilih');
        isValid = false;
      }

      // Validate price
      if (!$('#edit_price').val()) {
        $('#edit_price').addClass('is-invalid');
        $('#edit_priceError').text('Harga harus diisi');
        isValid = false;
      }

      return isValid;
    }

    function displayValidationErrors(errors) {
      for (const field in errors) {
        const errorMessage = errors[field][0];
        $(`#${field}`).addClass('is-invalid');
        $(`#${field}Error`).text(errorMessage);
      }
    }

    function displayEditValidationErrors(errors) {
      for (const field in errors) {
        const errorMessage = errors[field][0];
        $(`#edit_${field}`).addClass('is-invalid');
        $(`#edit_${field}Error`).text(errorMessage);
      }
    }

    function showDeleteModal(ids) {
      $('#btnConfirmDelete').data('ids', ids);
      $('#deleteModal').modal('show');
    }

    function showLoading() {
      if ($('.loading-overlay').length === 0) {
        $('body').append(`
          <div class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        `);
      }
    }

    function hideLoading() {
      $('.loading-overlay').remove();
    }
  });
</script>
<?php View::endSection(); ?>