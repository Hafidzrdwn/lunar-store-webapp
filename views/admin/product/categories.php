<?php View::extend('admin'); ?>
<?php View::startSection('title'); ?>
Data Kategori Produk
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

  .category-action-btn {
    margin: 0 3px;
  }

  #categoryTable_filter {
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

  .category-image {
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

  .description-cell {
    max-width: 350px;
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
        <h3>Data Kategori Produk</h3>
        <p class="text-subtitle text-muted">Kelola kategori produk.</p>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kategori Produk</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Daftar Kategori Produk</h4>
        <button type="button" class="btn btn-primary" id="btnAddCategory">
          <i class="fas fa-plus-circle"></i> Tambah Kategori
        </button>
      </div>
      <div class="card-body position-relative">
        <div class="table-responsive">
          <table class="table table-striped" id="categoryTable">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>#</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Slug</th>
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

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="categoryModalLabel">Tambah Kategori Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="categoryForm" enctype="multipart/form-data" autocomplete="off">
          <input type="hidden" id="categoryId" name="id">

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="title" class="form-label">Judul Kategori</label>
                <input type="text" class="form-control" id="title" name="title" required placeholder="Enter Judul Kategori">
                <div class="invalid-feedback" id="titleError"></div>
              </div>

              <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" readonly>
                <small class="text-muted">Slug akan dibuat otomatis dari judul</small>
                <div class="invalid-feedback" id="slugError"></div>
              </div>

              <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                <div class="invalid-feedback" id="descriptionError"></div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label for="image" class="form-label">Gambar Kategori</label>
                <div class="image-preview mb-2">
                  <img id="imagePreview" src="https://placehold.co/375x200" alt="Preview">
                </div>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="invalid-feedback" id="imageError"></div>
                <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, WEBP, GIF</small>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSaveCategory">Simpan</button>
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
        <p>Apakah Anda yakin ingin menghapus kategori yang dipilih?</p>
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
        <h5 class="modal-title" id="imageModalLabel">Gambar Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="fullImage" src="https://placehold.co" alt="Category Image" class="img-fluid">
      </div>
    </div>
  </div>
</div>
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<script>
  $(document).ready(function() {
    // Initialize DataTable
    const categoryTable = $('#categoryTable').DataTable({
      processing: true,
      serverSide: true,
      responsive: false,
      scrollX: true,
      ajax: {
        url: '<?= site_url('/modules/admin/product_categories.php') ?>',
        type: 'POST',
        data: function(d) {
          d.get_categories = true;
          return d;
        }
      },
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
          className: 'dt-center',
          render: function() {
            return '<div class="form-check"><input class="form-check-input category-select" type="checkbox"></div>';
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
          data: 'image',
          orderable: false,
          searchable: false,
          render: function(data, type, row) {
            if (type === 'display') {
              if (data) {
                return `<img src="${data}" alt="${row.title}" class="category-image view-image c-pointer" data-src="${data}">`;
              } else {
                return `<img src="https://placehold.co/300x200" alt="No Image" class="category-image">`;
              }
            }
            return data;
          }
        },
        {
          data: 'title',
          className: 'editable text-nowrap',
          render: function(data, type, row) {
            if (type === 'display') {
              return `<span data-field="title" data-id="${row.id}">${data}</span>`;
            }
            return data;
          }
        },
        {
          data: 'slug'
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
                <button type="button" class="btn btn-sm btn-primary category-action-btn btn-edit" data-id="${row.id}">
                  <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger category-action-btn btn-delete" data-id="${row.id}">
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
          const selectedIds = getSelectedCategoryIds();
          if (selectedIds.length > 0) {
            showDeleteModal(selectedIds);
          } else {
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Silakan pilih kategori yang akan dihapus terlebih dahulu!'
            });
          }
        }
      }]
    });

    // Select All Checkbox
    $('#selectAll').on('click', function() {
      $('.category-select').prop('checked', this.checked);
    });

    // Check if all checkboxes are selected
    $('#categoryTable tbody').on('change', '.category-select', function() {
      const allChecked = $('.category-select:checked').length === $('.category-select').length;
      $('#selectAll').prop('checked', allChecked);
    });

    // Get selected category IDs
    function getSelectedCategoryIds() {
      const selectedIds = [];
      $('.category-select:checked').each(function() {
        const rowData = categoryTable.row($(this).closest('tr')).data();
        selectedIds.push(rowData.id);
      });
      return selectedIds;
    }

    // Auto-generate slug from title
    $('#title').on('keyup', function() {
      const title = $(this).val();
      const slug = createSlug(title);
      $('#slug').val(slug);
    });

    // Function to create slug
    function createSlug(text) {
      return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(/[^\w\-]+/g, '') // Remove all non-word chars
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, ''); // Trim - from end of text
    }

    // Image preview
    $('#image').on('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#imagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
      } else {
        $('#imagePreview').attr('src', 'https://placehold.co/300x200');
      }
    });

    // View full image
    $('#categoryTable').on('click', '.view-image', function() {
      const src = $(this).data('src');
      $('#fullImage').attr('src', src);
      $('#imageModal').modal('show');
    });

    // Show Add Category Modal
    $('#btnAddCategory').on('click', function() {
      resetCategoryForm();
      $('#categoryModalLabel').text('Tambah Kategori Baru');
      $('#categoryModal').modal('show');
    });

    // Show Edit Category Modal
    $('#categoryTable').on('click', '.btn-edit', function() {
      const categoryId = $(this).data('id');
      resetCategoryForm();
      $('#categoryModalLabel').text('Edit Kategori');

      // Show loading overlay
      showLoading();

      // Fetch category data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_categories.php') ?>',
        type: 'GET',
        data: {
          categoryId: categoryId
        },
        success: function(response) {
          if (response.success) {
            const category = response.data;
            $('#categoryId').val(category.id);
            $('#title').val(category.title);
            $('#slug').val(category.slug);
            $('#description').val(category.description);

            if (category.image) {
              $('#imagePreview').attr('src', category.image);
            } else {
              $('#imagePreview').attr('src', 'https://placehold.co/375x200');
            }

            $('#categoryModal').modal('show');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Terjadi kesalahan saat mengambil data kategori'
            });
          }
          hideLoading();
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data kategori'
          });
          hideLoading();
        }
      });
    });

    // Save Category (Create/Update)
    $('#btnSaveCategory').on('click', function() {
      const categoryId = $('#categoryId').val();
      const isEdit = categoryId !== '';

      // Reset validation errors
      resetValidationErrors();

      // Show loading overlay
      showLoading();

      // Create FormData object for file upload
      const formData = new FormData($('#categoryForm')[0]);
      formData.append(isEdit ? 'editCategory' : 'saveCategory', 'true');
      if (isEdit) {
        formData.append('categoryId', categoryId);
      }

      // Submit form data
      $.ajax({
        url: '<?= site_url('/modules/admin/product_categories.php') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            $('#categoryModal').modal('hide');
            categoryTable.ajax.reload();
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
    $('#categoryTable').on('click', '.btn-delete', function() {
      const categoryId = $(this).data('id');
      showDeleteModal([categoryId]);
    });

    // Confirm Delete
    $('#btnConfirmDelete').on('click', function() {
      const selectedIds = $(this).data('ids');

      // Show loading overlay
      showLoading();

      $.ajax({
        url: '<?= site_url('/modules/admin/product_categories.php') ?>',
        type: 'POST',
        data: {
          ids: selectedIds,
          delete_category: true
        },
        success: function(response) {
          if (response.success) {
            $('#deleteModal').modal('hide');
            categoryTable.ajax.reload();
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
    $('#categoryTable').on('click', '.editable span', function() {
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
          url: '<?= site_url('/modules/admin/product_categories.php') ?>',
          type: 'POST',
          data: {
            id: id,
            field: field,
            value: newValue,
            updateField: true
          },
          success: function(response) {
            if (response.success) {
              // If it's the title field, we need to update the slug as well
              if (field === 'title') {
                categoryTable.ajax.reload();
              } else {
                cell.text(newValue || '-');
              }

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
    function resetCategoryForm() {
      $('#categoryForm')[0].reset();
      $('#categoryId').val('');
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