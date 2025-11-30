<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title"><?= $title ?></h4>
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Filters</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= site_url('admin/products/featured') ?>" method="get" id="filter-form">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="search">Search Products</label>
                                            <input type="text" class="form-control" id="search" name="search" 
                                                   placeholder="Search by name, description, or ID..." 
                                                   value="<?= esc($searchTerm ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="category">Filter by Category</label>
                                            <select class="form-control select2" id="category" name="category">
                                                <option value="">All Categories</option>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?= $category['id'] ?>" 
                                                        <?= (isset($categoryId) && $categoryId == $category['id']) ? 'selected' : '' ?>>
                                                        <?= esc($category['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-filter mr-1"></i> Apply Filters
                                            </button>
                                            <?php if (!empty($searchTerm) || !empty($categoryId)) : ?>
                                                <a href="<?= site_url('admin/products/featured') ?>" class="btn btn-outline-secondary w-100 mt-2">
                                                    <i class="fas fa-times mr-1"></i> Clear Filters
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="featured-products-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Product Status</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)) : ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No products found matching your criteria</td>
                                    </tr>
                                <?php else : ?>
                                    <?php $i = 1; foreach ($products as $product) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($product['image'])) : ?>
                                                        <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                                             alt="<?= esc($product['name']) ?>" 
                                                             class="img-thumbnail mr-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div style="display: none; width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d; margin-right: 12px;">
                                                            <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                                        </div>
                                                    <?php else: ?>
                                                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d; margin-right: 12px;">
                                                            <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($product['name']) ?></h6>
                                                        <small class="text-muted">SKU: <?= $product['id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($product['category_name'] ?? 'Uncategorized') ?></td>
                                            <td>
                                                <div>
                                                    Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                                    <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                                        <div class="text-muted small">PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                                    <?= $product['stock'] ?> in stock
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $product['product_status'] === 'ACTIVE' ? 'success' : 'danger' ?>">
                                                    <?= $product['product_status'] ?? 'ACTIVE' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $product['is_featured'] ? 'success' : 'secondary' ?>" id="status-badge-<?= $product['id'] ?>">
                                                    <?= $product['is_featured'] ? 'Featured' : 'Regular' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-<?= $product['is_featured'] ? 'warning' : 'primary' ?> toggle-featured" 
                                                        data-id="<?= $product['id'] ?>"
                                                        data-status="<?= $product['is_featured'] ?>">
                                                    <i class="fas fa-<?= $product['is_featured'] ? 'star' : 'star' ?>"></i>
                                                    <?= $product['is_featured'] ? ' Remove Featured' : ' Set as Featured' ?>
                                                </button>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to <span id="actionText"></span> this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Select a category',
        allowClear: true,
        width: '100%'
    });

    // Toggle featured status
    $('.toggle-featured').on('click', function() {
        const productId = $(this).data('id');
        const currentStatus = $(this).data('status');
        const action = currentStatus ? 'remove from featured' : 'set as featured';
        
        // Update modal text
        $('#actionText').text(action);
        
        // Show confirmation modal
        $('#confirmationModal').modal('show');
        
        // Handle confirm button click
        $('#confirmAction').off('click').on('click', function() {
            $.ajax({
                url: '<?= site_url('admin/products/toggle-featured/') ?>' + productId,
                type: 'POST',
                dataType: 'json',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Update button and status badge
                        const $button = $(`.toggle-featured[data-id="${productId}"]`);
                        const $badge = $(`#status-badge-${productId}`);
                        
                        if (response.is_featured) {
                            $button.removeClass('btn-primary').addClass('btn-warning')
                                .html('<i class="fas fa-star"></i> Remove Featured')
                                .data('status', 1);
                            $badge.removeClass('badge-secondary').addClass('badge-success').text('Featured');
                            toastr.success('Product has been marked as featured');
                        } else {
                            $button.removeClass('btn-warning').addClass('btn-primary')
                                .html('<i class="far fa-star"></i> Set as Featured')
                                .data('status', 0);
                            $badge.removeClass('badge-success').addClass('badge-secondary').text('Regular');
                            toastr.success('Product has been removed from featured');
                        }
                    } else {
                        toastr.error(response.message || 'An error occurred');
                    }
                    
                    $('#confirmationModal').modal('hide');
                },
                error: function() {
                    toastr.error('An error occurred while updating the product');
                    $('#confirmationModal').modal('hide');
                }
            });
        });
    });
    
    // Initialize DataTable with state saving
    if ($.fn.DataTable.isDataTable('#featured-products-table')) {
        $('#featured-products-table').DataTable().destroy();
    }
    
    $('#featured-products-table').DataTable({
        responsive: true,
        pageLength: 25,
        stateSave: true,
        stateDuration: -1, // Session storage
        order: [[1, 'asc']], // Sort by product name by default
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting on actions column
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search in table..."
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            // Add custom search box
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length select').addClass('form-control form-control-sm');
        }
    });
    
    // Prevent form submission on enter key in search field
    $('#search').on('keyup keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>
