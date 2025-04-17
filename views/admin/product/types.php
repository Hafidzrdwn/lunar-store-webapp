<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Data Tipe Produk
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

  .type-action-btn {
    margin: 0 3px;
  }

  #typeTable_filter {
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

  .description-cell {
    max-width: 350px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Data Tipe Produk</h3>
        <p class="text-subtitle text-muted">Kelola tipe produk.</p>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tipe Produk</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Tipe Produk</h4>
        <button type="button" class="btn btn-primary" id="btnAddType">
          <i class="fas fa-plus-circle"></i> Tambah Tipe
        </button>
      </div>
      <div class="card-body position-relative">
        <div class="table-responsive">
          <table class="table table-striped" id="typeTable">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>#</th>
                <th>Nama Aplikasi</th>
                <th>Nama Tipe</th>
                <th>Deskripsi</th>
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

<!-- Add/Edit Type Modal -->
<div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="typeModalLabel">Tambah Tipe Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="typeForm">
          <input type="hidden" id="typeId" name="id">

          <div class="mb-3">
            <label for="app_name" class="form-label">Nama Aplikasi</label>
            <select class="form-select" id="app_name" name="app_name" required>
              <option value="">Pilih Aplikasi</option>
              <!-- App Name will be loaded via AJAX -->
            </select>
            <div class="invalid-feedback" id="app_nameError"></div>
          </div>

          <div class="mb-3">
            <label for="type_name" class="form-label">Nama Tipe</label>
            <input type="text" class="form-control" id="type_name" name="type_name" required placeholder="Enter Nama Tipe">
            <div class="invalid-feedback" id="type_nameError"></div>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Masukkan Deskripsi Tipe"></textarea>
            <div class="invalid-feedback" id="descriptionError"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSaveType">Simpan</button>
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
        <p>Apakah Anda yakin ingin menghapus tipe produk yang dipilih?</p>
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
    loadAppName();

    // Initialize DataTable
    const typeTable = $('#typeTable').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: {
        url: '<?= site_url('/modules/admin/product_types.php') ?>',
        type: 'POST',
        data: function(d) {
          d.get_types = true;
          return d;
        }
      },
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
          className: 'dt-center',
          render: function() {
            return '<div class="form-check"><input class="form-check-input type-select" type="checkbox"></div>';
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
          data: 'app_name',
          className: 'editable',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="type_name" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'type_name',
          className: 'editable',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="type_name" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
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
          data: null,
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            return `
              <div class="d-flex">
                <button type="button" class="btn btn-sm btn-primary type-action-btn btn-edit" data-id="${row.id}">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger type-action-btn btn-delete" data-id="${row.id}">
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
          const selectedIds = getSelectedTypeIds();
          if (selectedIds.length > 0) {
            showDeleteModal(selectedIds);
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Silakan pilih tipe produk yang akan dihapus terlebih dahulu!'
            });
          }
        }
      }]
    });

    // Select All Checkbox
    $('#selectAll').on('click', function() {
      $('.type-select').prop('checked', this.checked);
    });

    // Check if all checkboxes are selected
    $('#typeTable tbody').on('change', '.type-select', function() {
      const allChecked = $('.type-select:checked').length === $('.type-select').length;
      $('#selectAll').prop('checked', allChecked);
    });

    // Get selected type IDs
    function getSelectedTypeIds() {
      const selectedIds = [];
      $('.type-select:checked').each(function() {
        const rowData = typeTable.row($(this).closest('tr')).data();
        selectedIds.push(rowData.id);
      });
      return selectedIds;
    }

    // Load App name for select box
    function loadAppName() {
      $.ajax({
        url: '<?= site_url('/modules/admin/product_types.php') ?>',
        type: 'GET',
        data: {
          get_app_name: true
        },
        success: function(response) {
          if (response.success) {
            const products = response.data;
            let options = '<option value="">Pilih Aplikasi</option>';

            products.forEach(function(product) {
              options += `<option value="${product.app_name}">${product.app_name}</option>`;
            });

            $('#app_name').html(options);
          }
        },
        error: function() {
          console.error('Failed to load app name');
        }
      });
    }

    // Show Add Type Modal
    $('#btnAddType').on('click', function() {
      resetTypeForm();
      $('#typeModalLabel').text('Tambah Tipe Baru');
      $('#typeModal').modal('show');
    });

    // Show Edit Type Modal
    $('#typeTable').on('click', '.btn-edit', function() {
      const typeId = $(this).data('id');
      resetTypeForm();
      $('#typeModalLabel').text('Edit Tipe Produk');

      // Show loading overlay
      showLoading();

      // Fetch type data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_types.php') ?>',
        type: 'GET',
        data: {
          typeId: typeId
        },
        success: function(response) {
          if (response.success) {
            const type = response.data;
            $('#typeId').val(type.id);
            $('#app_name').val(type.app_name);
            $('#type_name').val(type.type_name);
            $('#description').val(type.description);
            $('#typeModal').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Terjadi kesalahan saat mengambil data tipe'
            });
          }
          hideLoading();
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data tipe'
          });
          hideLoading();
        }
      });
    });

    // Save Type (Create/Update)
    $('#btnSaveType').on('click', function() {
      const typeId = $('#typeId').val();
      const isEdit = typeId !== '';

      // Reset validation errors
      resetValidationErrors();

      // Show loading overlay
      showLoading();

      // Submit form data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_types.php') ?>',
        type: 'POST',
        data: isEdit ?
          $('#typeForm').serialize() + '&editType=true&typeId=' + typeId : $('#typeForm').serialize() + '&saveType=true',
        success: function(response) {
          if (response.success) {
            $('#typeModal').modal('hide');
            typeTable.ajax.reload();
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
    $('#typeTable').on('click', '.btn-delete', function() {
      const typeId = $(this).data('id');
      showDeleteModal([typeId]);
    });

    // Confirm Delete
    $('#btnConfirmDelete').on('click', function() {
      const selectedIds = $(this).data('ids');

      // Show loading overlay
      showLoading();

      $.ajax({
        url: '<?= site_url('/modules/admin/product_types.php') ?>',
        type: 'POST',
        data: {
          ids: selectedIds,
          delete_type: true
        },
        success: function(response) {
          if (response.success) {
            $('#deleteModal').modal('hide');
            typeTable.ajax.reload();
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
    $('#typeTable').on('click', '.editable span', function() {
      const cell = $(this);
      const field = cell.data('field');
      const id = cell.data('id');
      const currentValue = cell.text();

      // Don't allow editing if already in edit mode
      if (cell.find('input, textarea').length > 0) {
        return;
      }

      let inputElement;

      if (field === 'description') {
        // Create textarea for description field
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
          url: '<?= site_url('/modules/admin/product_types.php') ?>',
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
    function resetTypeForm() {
      $('#typeForm')[0].reset();
      $('#typeId').val('');
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