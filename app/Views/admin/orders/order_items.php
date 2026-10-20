<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box mr-2"></i>
                        Order Items Management
                    </h5>

                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="orderitemsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order #</th>
                                    <th>Order Date</th>
                                    <th>Customer</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>DP</th>
                                    <th>Harga PO</th>
                                    
                                    <th>Order Status</th>
                                   
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orderItems)): ?>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td><?= $item['id'] ?></td>
                                            <td>
                                                <a href="<?= base_url('/admin/orders/view/' . $item['order_id']) ?>" class="text-primary">
                                                    <?= $item['order_number'] ?>
                                                </a>
                                            </td>
                                            <td><?= date('d M Y', strtotime($item['order_date'])) ?></td>
                                            <td>
                                                <div><?= esc($item['customer_name']) ?></div>
                                                <small class="text-muted"><?= esc($item['customer_phone']) ?></small>
                                            </td>
                                            <td><?= esc($item['product_name']) ?></td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td><?= number_format($item['price'], 2) ?></td>
                                            <td><?= number_format($item['po_price_desc'], 2) ?></td>
                
                                            <td>
                                                <span class="badge badge-<?= $item['order_status'] == 'completed' ? 'success' : ($item['order_status'] == 'cancelled' ? 'danger' : ($item['order_status'] == 'processing' ? 'warning' : 'info')) ?>">
                                                    <?= ucfirst($item['order_status']) ?>
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('/admin/orders/view/' . $item['order_id']) ?>" 
                                                       class="btn btn-info" title="View Order">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No order items found</td>
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
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
function updateItemStatus(itemId, status) {
    if (!confirm('Are you sure you want to update this item status to "' + status.replace('_', ' ') + '"?')) {
        return;
    }
    
    fetch('<?= base_url("/admin/orders/updateItemStatus") ?>/' + itemId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'status=' + status
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the item status.');
    });
}

$(document).ready(function() {
    $('#orderitemsTable').DataTable({
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        },
        paging: true, 
        searching: true,
        order: [[0, 'desc']]
    });
});
</script>
<?= $this->endSection() ?>
