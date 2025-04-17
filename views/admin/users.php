<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Data Master Pengguna
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

  .user-action-btn {
    margin: 0 3px;
  }

  #userTable_filter {
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
</style>
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Data Master Pengguna</h3>
        <p class="text-subtitle text-muted">Kelola data pengguna sistem</p>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Pengguna</h4>
        <button type="button" class="btn btn-primary" id="btnAddUser">
          <i class="fas fa-plus-circle"></i> Tambah Pengguna
        </button>
      </div>
      <div class="card-body position-relative">
        <div class="table-responsive">
          <table class="table table-striped" id="userTable">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>#</th>
                <th>Username</th>
                <th>Fullname</th>
                <th>Email</th>
                <th>Phone number</th>
                <th>Address</th>
                <th>Tanggal Bergabung</th>
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

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Tambah Pengguna Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="userForm">
          <input type="hidden" id="userId" name="id">

          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required placeholder="Enter username">
            <div class="invalid-feedback" id="usernameError"></div>
          </div>

          <div class="mb-3">
            <label for="name" class="form-label">Fullname</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter fullname">
            <div class="invalid-feedback" id="nameError"></div>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email">
            <div class="invalid-feedback" id="emailError"></div>
          </div>

          <div class="mb-3 password-field">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
            <div class="invalid-feedback" id="passwordError"></div>
          </div>

          <div class="mb-3 password-field">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
            <div class="invalid-feedback" id="password_confirmationError"></div>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input class="form-control" type="text" id="phone" name="phone" placeholder="Enter phone number">
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" placeholder="Enter address"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSaveUser">Simpan</button>
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
        <p>Apakah Anda yakin ingin menghapus pengguna yang dipilih?</p>
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
    // Initialize DataTable
    const userTable = $('#userTable').DataTable({
      processing: true,
      serverSide: true,
      responsive: false,
      scrollX: true,
      ajax: {
        url: '<?= site_url('/modules/admin/users.php') ?>',
        type: 'POST',
        data: function(d) {
          d.get_users = true;
          return d;
        }
      },
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
          className: 'dt-center',
          render: function() {
            return '<div class="form-check"><input class="form-check-input user-select" type="checkbox"></div>';
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
          data: 'username',
          className: 'editable',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="username" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'name',
          className: 'editable text-nowrap',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="name" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'email',
          className: 'editable',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="email" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'phone',
          className: 'editable',
          render: function(data, type, row) {
            data = data ? data : '-';
            if (type === 'display') {
              return `<span data-field="phone" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'address',
          className: 'editable',
          render: function(data, type, row) {
            data = data ? data : '-';
            if (type === 'display') {
              return `<span data-field="address" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'created_at'
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            return `
              <div class="d-flex">
                <button type="button" class="btn btn-sm btn-primary user-action-btn btn-edit" data-id="${row.id}">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger user-action-btn btn-delete" data-id="${row.id}">
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
          const selectedIds = getSelectedUserIds();
          if (selectedIds.length > 0) {
            showDeleteModal(selectedIds);
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Silakan pilih pengguna yang akan dihapus terlebih dahulu!'
            });
          }
        }
      }]
    });

    // Select All Checkbox
    $('#selectAll').on('click', function() {
      $('.user-select').prop('checked', this.checked);
    });

    // Check if all checkboxes are selected
    $('#userTable tbody').on('change', '.user-select', function() {
      const allChecked = $('.user-select:checked').length === $('.user-select').length;
      $('#selectAll').prop('checked', allChecked);
    });

    // Get selected user IDs
    function getSelectedUserIds() {
      const selectedIds = [];
      $('.user-select:checked').each(function() {
        const rowData = userTable.row($(this).closest('tr')).data();
        selectedIds.push(rowData.id);
      });
      return selectedIds;
    }

    // Show Add User Modal
    $('#btnAddUser').on('click', function() {
      resetUserForm();
      $('#userModalLabel').text('Tambah Pengguna Baru');
      $('.password-field').show();
      $('#userModal').modal('show');
    });

    // Show Edit User Modal
    $('#userTable').on('click', '.btn-edit', function() {
      const userId = $(this).data('id');
      resetUserForm();
      $('#userModalLabel').text('Edit Pengguna');
      $('.password-field').hide();

      // Show loading overlay
      showLoading();

      // Fetch user data
      $.ajax({
        url: `<?= site_url('/modules/admin/users.php') ?>`,
        type: 'GET',
        data: {
          id: userId,
          get_user: true
        },
        success: function(response) {
          if (response.success) {
            const user = response.data;
            $('#userId').val(user.id);
            $('#username').val(user.username);
            $('#name').val(user.name);
            $('#email').val(user.email);
            $('#phone').val(user.phone);
            $('#address').val(user.address);
            $('#userModal').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Terjadi kesalahan saat mengambil data pengguna'
            });
          }
          hideLoading();
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data pengguna'
          });
          hideLoading();
        }
      });
    });

    // Save User (Create/Update)
    $('#btnSaveUser').on('click', function() {
      const userId = $('#userId').val();
      const isEdit = userId !== '';

      // Reset validation errors
      resetValidationErrors();

      // Show loading overlay
      showLoading();

      // Submit form data
      $.ajax({
        url: '<?= site_url('/modules/admin/users.php') ?>',
        type: 'POST',
        data: isEdit ?
          $('#userForm').serialize() + '&edit_user=true&id=' + userId : $('#userForm').serialize() + '&save_user=true',
        success: function(response) {
          if (response.success) {
            $('#userModal').modal('hide');
            userTable.ajax.reload();
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
    $('#userTable').on('click', '.btn-delete', function() {
      const userId = $(this).data('id');
      showDeleteModal([userId]);
    });

    // Confirm Delete
    $('#btnConfirmDelete').on('click', function() {
      const selectedIds = $(this).data('ids');

      // Show loading overlay
      showLoading();

      $.ajax({
        url: '<?= site_url('/modules/admin/users.php') ?>',
        type: 'POST',
        data: {
          ids: selectedIds,
          delete_user: true
        },
        success: function(response) {
          if (response.success) {
            $('#deleteModal').modal('hide');
            userTable.ajax.reload();
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
    $('#userTable').on('click', '.editable span', function() {
      const cell = $(this);
      const field = cell.data('field');
      const id = cell.data('id');
      const currentValue = cell.text();

      // Don't allow editing if already in edit mode
      if (cell.find('input, select').length > 0) {
        return;
      }

      // Create input element for fields
      let inputElement;

      if (field === 'address') {
        // Create textarea for address field
        inputElement = $(`<textarea class="edit-input">${currentValue}</textarea>`);
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
        if (newValue === currentValue) {
          cell.text(currentValue);
          return;
        }

        // Show loading overlay
        showLoading();

        // Save the updated value
        $.ajax({
          url: '<?= site_url('/modules/admin/users.php') ?>',
          type: 'POST',
          data: {
            id: id,
            field: field,
            value: newValue,
            update_field: true
          },
          success: function(response) {
            if (response.success) {
              cell.text(newValue);

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
    function resetUserForm() {
      $('#userForm')[0].reset();
      $('#userId').val('');
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