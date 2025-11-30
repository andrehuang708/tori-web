<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title"><?= $title ?></h4>
                    <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Product
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="productsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Featured</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($products) && !empty($products)) : ?>
                                    <?php foreach ($products as $product) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($product['image'])) : ?>
                                                        <div style="margin-right: 12px;">
                                                            <img src="<?= base_url('uploads/products/' . $product['image']) ?>" 
                                                                 alt="<?= esc($product['name']) ?>" 
                                                                 class="img-thumbnail" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        </div>
                                                        <div class="img-thumbnail" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; border-radius: 4px; display: none; align-items: center; justify-content: center; color: #6c757d; margin-right: 12px;">
                                                            <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="img-thumbnail" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; border-radius: 4px; display: flex !important; align-items: center; justify-content: center; color: #6c757d; margin-right: 12px;">
                                                            <i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($product['name']) ?></h6>
                                                        <small class="text-muted">SKU: <?= $product['sku'] ?? $product['id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    DP : Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                                    <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                                        <div class="text-muted small"><b>PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?></b></div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                                    <?= $product['stock'] ?> in stock
                                                </span>
                                            </td>
                                            <td><?= $product['category_name'] ?? 'Uncategorized' ?></td>
                                            <td>
                                                <span class="badge badge-<?= $product['is_featured'] ? 'success' : 'secondary' ?>">
                                                    <?= $product['is_featured'] ? 'Featured' : 'Regular' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $product['product_status'] === 'ACTIVE' ? 'success' : 'danger' ?>">
                                                    <?= $product['product_status'] ?? 'ACTIVE' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('admin/products/edit/' . $product['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-<?= $product['is_featured'] ? 'warning' : 'primary' ?> toggle-featured" 
                                                        data-id="<?= $product['id'] ?>"
                                                        data-status="<?= $product['is_featured'] ?>">
                                                    <i class="fas fa-<?= $product['is_featured'] ? 'star' : 'star' ?>"></i>
                                                </button>
                                                <a href="https://wa.me/?text=<?= urlencode('Check out this product: ' . $product['name'] . "\n" . 'DP: Rp ' . number_format($product['price'], 0, ',', '.') . (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0 ? "\nHarga PO : Rp " . number_format($product['po_price_desc'], 0, ',', '.') : '') . "\n" . 'View details: ' . site_url('products/detail/' . $product['slug'])) ?>" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-success"
                                                   title="Share on WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                                <a href="<?= site_url('admin/products/delete/' . $product['id']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No products found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with server-side processing
    var table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= site_url('admin/products') ?>',
            type: 'POST',
            data: function(d) {
                d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                return d;
            },
            error: function(xhr, error, code) {
                console.log('AJAX Error:', xhr.responseText);
                console.log('Error details:', {xhr: xhr, error: error, code: code});
                
                // Try to parse JSON response
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'error' && response.redirect) {
                        // Session expired, redirect to login
                        alert('Your session has expired. Redirecting to login...');
                        window.location.href = response.redirect;
                        return;
                    }
                } catch (e) {
                    // Not JSON, handle as regular error
                }
                
                alert('Error loading data. Check console for details.');
            }
        },
        columns: [
            { 
                data: 'name',
                render: function(data, type, row) {
                    var imageHtml = '';
                    if (row.image) {
                        imageHtml = '<div style="position: relative; width: 50px; height: 50px; margin-right: 12px;">' +
                                   '<img src="<?= base_url('uploads/products/') ?>' + row.image + 
                                   '" alt="' + row.name + '" class="img-thumbnail" ' +
                                   'style="width: 50px; height: 50px; object-fit: cover;" ' +
                                   'onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">' +
                                   '<div style="display: none; width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d;">' +
                                   '<i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>' +
                                   '</div>' +
                                   '</div>';
                    } else {
                        imageHtml = '<div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d; margin-right: 12px;">' +
                                   '<i class="fas fa-image" style="font-size: 1rem; opacity: 0.5;"></i>' +
                                   '</div>';
                    }
                    return '<div class="d-flex align-items-center">' +
                           imageHtml +
                           '<div>' +
                           '<h6 class="mb-0">' + row.name + '</h6>' +
                           '<small class="text-muted">SKU: ' + (row.sku || row.id) + '</small>' +
                           '</div>' +
                           '</div>';
                }
            },
            { 
                data: 'price',
                render: function(data, type, row) {
                    return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                }
            },
            { 
                data: 'stock',
                render: function(data, type, row) {
                    var badgeClass = data > 0 ? 'success' : 'danger';
                    return '<span class="badge badge-' + badgeClass + '">' + data + ' in stock</span>';
                }
            },
            { 
                data: 'category_name',
                render: function(data, type, row) {
                    return data || 'Uncategorized';
                }
            },
            { 
                data: 'is_featured',
                render: function(data, type, row) {
                    var badgeClass = data ? 'success' : 'secondary';
                    var text = data ? 'Featured' : 'Regular';
                    return '<span class="badge badge-' + badgeClass + '">' + text + '</span>';
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return '<a href="<?= site_url('admin/products/edit/') ?>' + row.id + '" class="btn btn-sm btn-info">' +
                           '<i class="fas fa-edit"></i>' +
                           '</a> ' +
                           '<button class="btn btn-sm btn-' + (row.is_featured ? 'warning' : 'primary') + ' toggle-featured" ' +
                           'data-id="' + row.id + '" data-status="' + row.is_featured + '">' +
                           '<i class="fas fa-star"></i>' +
                           '</button> ' +
                           '<a href="<?= site_url('admin/products/delete/') ?>' + row.id + '" ' +
                           'class="btn btn-sm btn-danger" ' +
                           'onclick="return confirm(\'Are you sure you want to delete this product?\')">' +
                           '<i class="fas fa-trash"></i>' +
                           '</a>';
                }
            }
        ],
        pageLength: 20,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        }
    });
    
    // Toggle featured status
    $(document).on('click', '.toggle-featured', function() {
        const productId = $(this).data('id');
        const currentStatus = $(this).data('status');
        const action = currentStatus ? 'remove from featured' : 'set as featured';
        
        if (confirm('Are you sure you want to ' + action + ' this product?')) {
            $.ajax({
                url: '<?= site_url('admin/products/toggle-featured/') ?>' + productId,
                type: 'POST',
                dataType: 'json',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        toastr.success(response.message || 'Featured status updated successfully');
                    } else {
                        toastr.error(response.message || 'An error occurred');
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating the product');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
