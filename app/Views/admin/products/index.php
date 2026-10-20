<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title"><?= $title ?></h4>
                    <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Product
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success m-3">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive m-0">
                        <table class="table table-bordered table-hover mb-0" id="productsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($products) && !empty($products)) : ?>
                                    <?php foreach ($products as $product) : ?>
                                        <tr>
                                            <td><?= $product['id'] ?></td>
                                            <td><?= $product['sku'] ?? 'N/A' ?></td>
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
                                                        <span class="badge badge-secondary">
                                                   <?= $product['category_name'] ?? 'Uncategorized' ?>
                                                        </span>    
                                                        <br>
                                                        <small class="text-muted">
                                                            DP: Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                                            <?php if (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0): ?>
                                                                | PO: Rp <?= number_format($product['po_price_desc'], 0, ',', '.') ?>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                </div>
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
                                                <a href="https://wa.me/?text=<?= urlencode('Check out this product: ' . $product['name'] . "\n" . 'DP: Rp ' . number_format($product['price'], 0, ',', '.') . (!empty($product['po_price_desc']) && $product['po_price_desc'] > 0 ? "\nHarga PO : Rp " . number_format($product['po_price_desc'], 0, ',', '.') : '') . "\n" . 'View details: ' . site_url('products/' . $product['slug'])) ?>" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-success"
                                                   title="Share on WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                                <form action="<?= site_url('admin/products/delete/' . $product['id']) ?>" method="post" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?')"
                                                      title="Delete">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No products found</td>
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']], // Sort by first column (ID) descending
        responsive: true,
        columnDefs: [
            { 
                targets: [0, 1, 3], // Hide ID, SKU, Status on mobile
                className: 'desktop'
            },
            { 
                targets: [2], // Keep only Name visible on mobile (price is inside it)
                className: 'all'
            }
        ]
    });
});
</script>
<?= $this->endSection() ?>
