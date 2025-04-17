<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Data Produk
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

  .product-action-btn {
    margin: 0 3px;
  }

  #productTable_filter {
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

  .product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
  }

  .image-preview {
    width: 100%;
    height: 200px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    background-color: #f8f9fa;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin-bottom: 10px;
  }

  .image-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }

  .description-cell,
  .notes-cell {
    max-width: 450px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .badge-status {
    font-size: 0.8rem;
    padding: 0.35em 0.65em;
  }

  .form-switch {
    padding-left: 2.5em;
  }

  .form-switch .form-check-input {
    width: 2em;
    margin-left: -2.5em;
  }

  .view-image {
    transition: scale 0.3s ease;
  }

  .view-image:hover {
    scale: 1.1;
  }
</style>
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Data Produk</h3>
        <p class="text-subtitle text-muted">Kelola data produk.</p>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Produk</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Produk</h4>
        <button type="button" class="btn btn-primary" id="btnAddProduct">
          <i class="fas fa-plus-circle"></i> Tambah Produk
        </button>
      </div>
      <div class="card-body position-relative">
        <div class="table-responsive">
          <table class="table table-striped" id="productTable">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>#</th>
                <th>Cover Image</th>
                <th>Nama Aplikasi</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Catatan</th>
                <th>Has Type?</th>
                <th>Stok Tersedia?</th>
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

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Tambah Produk Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="productForm" enctype="multipart/form-data">
          <input type="hidden" id="productId" name="id">

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="app_name" class="form-label">Nama Aplikasi</label>
                <input type="text" class="form-control" id="app_name" name="app_name" required placeholder="Enter Nama Aplikasi">
                <div class="invalid-feedback" id="app_nameError"></div>
              </div>

              <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                  <option value="">Pilih Kategori</option>
                  <!-- Categories will be loaded via AJAX -->
                </select>
                <div class="invalid-feedback" id="category_idError"></div>
              </div>

              <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan Deskripsi Aplikasi"></textarea>
                <div class="invalid-feedback" id="descriptionError"></div>
              </div>

              <div class="mb-3">
                <label for="notes" class="form-label">Catatan</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan Catatan"></textarea>
                <div class="invalid-feedback" id="notesError"></div>
              </div>

              <div class="mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="has_type" name="has_type" value="1">
                  <label class="form-check-label" for="has_type">Memiliki Tipe</label>
                </div>
                <div class="invalid-feedback" id="has_typeError"></div>
              </div>

              <div class="mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="ready_stock" name="ready_stock" value="1">
                  <label class="form-check-label" for="ready_stock">Stok Tersedia</label>
                </div>
                <div class="invalid-feedback" id="ready_stockError"></div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label for="cover_img" class="form-label">Gambar Produk</label>
                <div class="image-preview mb-2">
                  <img id="imagePreview" src="https://placehold.co/375x200" alt="Preview">
                </div>
                <input type="file" class="form-control" id="cover_img" name="cover_img" accept="image/*">
                <div class="invalid-feedback" id="cover_imgError"></div>
                <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, GIF, WEBP</small>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSaveProduct">Simpan</button>
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
        <p>Apakah Anda yakin ingin menghapus produk yang dipilih?</p>
        <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- View Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Gambar Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="fullImage" src="/placeholder.svg" alt="Product Image" class="img-fluid">
      </div>
    </div>
  </div>
</div>
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<script>
  $(document).ready(function() {
    // Load categories for select box
    loadCategories();

    // Initialize DataTable
    const productTable = $('#productTable').DataTable({
      processing: true,
      serverSide: true,
      responsive: false,
      scrollX: true,
      ajax: {
        url: '<?= site_url('/modules/admin/products.php') ?>',
        type: 'POST',
        data: function(d) {
          d.get_products = true;
          return d;
        }
      },
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
          className: 'dt-center',
          render: function() {
            return '<div class="form-check"><input class="form-check-input product-select" type="checkbox"></div>';
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
          data: 'cover_img',
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            if (type === 'display') {
              if (data) {
                return `<img src="${data}" alt="${row.app_name}" class="product-image view-image" data-src="${data}">`;
              } else {
                return `<img src="https://placehold.co/300x200" alt="No Image" class="product-image">`;
              }
            }
            return data;
          }
        },
        {
          data: 'app_name',
          className: 'editable',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="app_name" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'category_name'
        },
        {
          data: 'description',
          className: 'editable description-cell',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="description" data-id="${row.id}" title="${data}">${data || '-'}</span>`;
            }
            return data;
          }
        },
        {
          data: 'notes',
          className: 'editable notes-cell',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="notes" data-id="${row.id}" title="${data}">${data || '-'}</span>`;
            }
            return data;
          }
        },
        {
          data: 'has_type',
          render: function(data, type, row) {
            if (type === 'display') {
              return data == 1 ?
                '<span class="badge bg-success badge-status">Ya</span>' :
                '<span class="badge bg-secondary badge-status">Tidak</span>';
            }
            return data;
          }
        },
        {
          data: 'ready_stock',
          render: function(data, type, row) {
            if (type === 'display') {
              return data == 1 ?
                '<span class="badge bg-success badge-status">Ya</span>' :
                '<span class="badge bg-danger badge-status">Tidak</span>';
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
                <button type="button" class="btn btn-sm btn-primary product-action-btn btn-edit" data-id="${row.id}">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger product-action-btn btn-delete" data-id="${row.id}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            `;
          }
        }
      ],
      order: [
        [1, 'asc']
      ],
      dom: 'Bfrtip',
      buttons: [{
        text: '<i class="fas fa-trash"></i> Hapus Terpilih',
        className: 'btn btn-danger',
        action: function() {
          const selectedIds = getSelectedProductIds();
          if (selectedIds.length > 0) {
            showDeleteModal(selectedIds);
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Silakan pilih produk yang akan dihapus terlebih dahulu!'
            });
          }
        }
      }]
    });

    // Select All Checkbox
    $('#selectAll').on('click', function() {
      $('.product-select').prop('checked', this.checked);
    });

    // Check if all checkboxes are selected
    $('#productTable tbody').on('change', '.product-select', function() {
      const allChecked = $('.product-select:checked').length === $('.product-select').length;
      $('#selectAll').prop('checked', allChecked);
    });

    // Get selected product IDs
    function getSelectedProductIds() {
      const selectedIds = [];
      $('.product-select:checked').each(function() {
        const rowData = productTable.row($(this).closest('tr')).data();
        selectedIds.push(rowData.id);
      });
      return selectedIds;
    }

    // Load categories for select box
    function loadCategories() {
      $.ajax({
        url: '<?= site_url('/modules/admin/products.php') ?>',
        type: 'GET',
        data: {
          get_categories: true
        },
        success: function(response) {
          if (response.success) {
            const categories = response.data;
            let options = '<option value="">Pilih Kategori</option>';

            categories.forEach(function(category) {
              options += `<option value="${category.id}">${category.title}</option>`;
            });

            $('#category_id').html(options);
          }
        },
        error: function() {
          console.error('Failed to load categories');
        }
      });
    }

    // Image preview
    $('#cover_img').on('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#imagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
      } else {
        $('#imagePreview').attr('src', 'https://placehold.co/375x200');
      }
    });

    // View full image
    $('#productTable').on('click', '.view-image', function() {
      const src = $(this).data('src');
      $('#fullImage').attr('src', src);
      $('#imageModal').modal('show');
    });

    // Show Add Product Modal
    $('#btnAddProduct').on('click', function() {
      resetProductForm();
      $('#productModalLabel').text('Tambah Produk Baru');
      $('#productModal').modal('show');
    });

    // Show Edit Product Modal
    $('#productTable').on('click', '.btn-edit', function() {
      const productId = $(this).data('id');
      resetProductForm();
      $('#productModalLabel').text('Edit Produk');

      // Show loading overlay
      showLoading();

      // Fetch product data
      $.ajax({
        url: '<?= site_url('/modules/admin/products.php') ?>',
        type: 'GET',
        data: {
          productId: productId
        },
        success: function(response) {
          if (response.success) {
            const product = response.data;
            $('#productId').val(product.id);
            $('#app_name').val(product.app_name);
            $('#category_id').val(product.category_id);
            $('#description').val(product.description);
            $('#notes').val(product.notes);
            $('#has_type').prop('checked', product.has_type == 1);
            $('#ready_stock').prop('checked', product.ready_stock == 1);

            if (product.cover_img) {
              $('#imagePreview').attr('src', product.cover_img);
            } else {
              $('#imagePreview').attr('src', 'https://placehold.co/375x200');
            }

            $('#productModal').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Terjadi kesalahan saat mengambil data produk'
            });
          }
          hideLoading();
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data produk'
          });
          hideLoading();
        }
      });
    });

    // Save Product (Create/Update)
    $('#btnSaveProduct').on('click', function() {
      const productId = $('#productId').val();
      const isEdit = productId !== '';

      // Reset validation errors
      resetValidationErrors();

      // Show loading overlay
      showLoading();

      // Create FormData object for file upload
      const formData = new FormData($('#productForm')[0]);
      formData.append(isEdit ? 'editProduct' : 'saveProduct', 'true');
      if (isEdit) {
        formData.append('productId', productId);
      }

      // Handle checkbox values
      if (!$('#has_type').is(':checked')) {
        formData.append('has_type', '0');
      }

      if (!$('#ready_stock').is(':checked')) {
        formData.append('ready_stock', '0');
      }

      // Submit form data
      $.ajax({
        url: '<?= site_url('/modules/admin/products.php') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            $('#productModal').modal('hide');
            productTable.ajax.reload();
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

    // Show Delete Confirmation Modal
    $('#productTable').on('click', '.btn-delete', function() {
      const productId = $(this).data('id');
      showDeleteModal([productId]);
    });

    // Confirm Delete
    $('#btnConfirmDelete').on('click', function() {
      const selectedIds = $(this).data('ids');

      // Show loading overlay
      showLoading();

      $.ajax({
        url: '<?= site_url('/modules/admin/products.php') ?>',
        type: 'POST',
        data: {
          ids: selectedIds,
          delete_product: true
        },
        success: function(response) {
          if (response.success) {
            $('#deleteModal').modal('hide');
            productTable.ajax.reload();
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

    // Inline Editing
    $('#productTable').on('click', '.editable span', function() {
      const cell = $(this);
      const field = cell.data('field');
      const id = cell.data('id');
      const currentValue = cell.text();

      // Don't allow editing if already in edit mode
      if (cell.find('input, textarea').length > 0) {
        return;
      }

      let inputElement;

      if (field === 'description' || field === 'notes') {
        // Create textarea for description and notes fields
        inputElement = $(`<textarea class="edit-input">${currentValue === '-' ? '' : currentValue}</textarea>`);
      } else {
        // Create input element for other fields
        inputElement = $(`<input type="text" class="edit-input" value="${currentValue}">`);
      }

      // Replace the cell content with the input element
      cell.html(inputElement);
      inputElement.focus();

      // Handle input blur event (save on blur)
      inputElement.on('blur', function() {
        const newValue = $(this).val();

        // If value hasn't changed, just restore the original text
        if ((newValue === currentValue) || (currentValue === '-' && newValue === '')) {
          cell.text(currentValue);
          return;
        }

        // Show loading overlay
        showLoading();

        // Save the updated value
        $.ajax({
          url: '<?= site_url('/modules/admin/products.php') ?>',
          type: 'POST',
          data: {
            id: id,
            field: field,
            value: newValue,
            updateField: true
          },
          success: function(response) {
            if (response.success) {
              cell.text(newValue || '-');

              // Show success toast
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil diperbarui',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              });
            } else {
              // Restore original value and show error
              cell.text(currentValue);
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Terjadi kesalahan saat memperbarui data'
              });
            }
            hideLoading();
          },
          error: function() {
            // Restore original value and show error
            cell.text(currentValue);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Terjadi kesalahan saat memperbarui data'
            });
            hideLoading();
          }
        });
      });

      // Handle Enter key press
      inputElement.on('keypress', function(e) {
        if (e.which === 13) {
          $(this).blur();
        }
      });

      // Handle Escape key press (cancel edit)
      inputElement.on('keydown', function(e) {
        if (e.which === 27) {
          cell.text(currentValue);
        }
      });
    });

    // Helper Functions
    function resetProductForm() {
      $('#productForm')[0].reset();
      $('#productId').val('');
      $('#imagePreview').attr('src', 'https://placehold.co/375x200');
      resetValidationErrors();
    }

    function resetValidationErrors() {
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').text('');
    }

    function displayValidationErrors(errors) {
      for (const field in errors) {
        const errorMessage = errors[field][0];
        $(`#${field}`).addClass('is-invalid');
        $(`#${field}Error`).text(errorMessage);
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