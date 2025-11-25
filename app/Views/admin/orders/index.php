<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Pesanan</h4>
                    <div class="btn-group">
                        <a href="<?= site_url('admin/orders') ?>" class="btn btn-sm btn-<?= !$status ? 'primary' : 'secondary' ?>">
                            <i class="fas fa-list mr-1"></i> Semua
                        </a>
                        <a href="<?= site_url('admin/orders/pending') ?>" class="btn btn-sm btn-<?= $status === 'pending' ? 'primary' : 'secondary' ?>">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </a>
                        <a href="<?= site_url('admin/orders/processing') ?>" class="btn btn-sm btn-<?= $status === 'processing' ? 'primary' : 'secondary' ?>">
                            <i class="fas fa-sync-alt mr-1"></i> Diproses
                        </a>
                        <a href="<?= site_url('admin/orders/completed') ?>" class="btn btn-sm btn-<?= $status === 'completed' ? 'success' : 'secondary' ?>">
                            <i class="fas fa-check-circle mr-1"></i> Selesai
                        </a>
                        <a href="<?= site_url('admin/orders/cancelled') ?>" class="btn btn-sm btn-<?= $status === 'cancelled' ? 'danger' : 'secondary' ?>">
                            <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="ordersTable" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>No. HP</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)) : ?>
                                    <?php foreach ($orders as $order) : ?>
                                        <tr>
                                            <td><?= $order['id'] ?></td>
                                            <td>
                                                <a href="<?= site_url('admin/orders/view/' . $order['id']) ?>">
                                                    #<?= $order['order_number'] ?>
                                                </a>
                                            </td>
                                            <td><?= esc($order['customer_name']) ?></td>
                                            <td><?= esc($order['customer_phone']) ?></td>
                                            <td data-order="<?= $order['subtotal'] ?>">
                                                Rp <?= number_format($order['subtotal'], 0, ',', '.') ?>
                                            </td>
                                            <td data-order="<?= $order['status'] ?>">
                                                <?= $orderModel->getStatusBadge($order['status']) ?>
                                            </td>
                                            <td data-order="<?= $order['payment_status'] ?? 'pending' ?>">
                                                <?php 
                                                $paymentStatus = $order['payment_status'] ?? 'pending';
                                                $paymentBadgeClass = 'warning';
                                                $paymentText = 'Pending';
                                                
                                                if ($paymentStatus == 'full_paid') {
                                                    $paymentBadgeClass = 'success';
                                                    $paymentText = 'Full Paid';
                                                } elseif ($paymentStatus == 'dp_accepted') {
                                                    $paymentBadgeClass = 'info';
                                                    $paymentText = 'DP Accepted';
                                                }
                                                ?>
                                                <span class="badge badge-<?= $paymentBadgeClass ?>"><?= $paymentText ?></span>
                                            </td>
                                            <td data-order="<?= strtotime($order['created_at']) ?>">
                                                <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="<?= site_url('admin/orders/view/' . $order['id']) ?>" class="btn btn-sm btn-primary" title="Lihat Detail">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'completed') : ?>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown<?= $order['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <div class="dropdown-menu " aria-labelledby="statusDropdown<?= $order['id'] ?>">
                                                            <a class="dropdown-item status-update" href="#" data-id="<?= $order['id'] ?>" data-status="processing">
                                                                <i class="fas fa-sync-alt me-2 text-primary"></i>Proses
                                                            </a>
                                                            <a class="dropdown-item status-update" href="#" data-id="<?= $order['id'] ?>" data-status="completed">
                                                                <i class="fas fa-check-circle me-2 text-success"></i>Selesai
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger status-update" href="#" data-id="<?= $order['id'] ?>" data-status="cancelled">
                                                                <i class="fas fa-times-circle me-2"></i>Batalkan
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?> -->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p class="mb-0">Tidak ada pesanan ditemukan</p>
                                            </div>
                                        </td>
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
$(document).ready(function() {
    // Initialize DataTable if the table exists on the page
    const $table = $('#ordersTable');
    if ($table.length) {
        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#ordersTable')) {
            $table.DataTable().destroy();
        }
        
        // Initialize DataTable with options
        $table.DataTable({
            responsive: true,
            paging: false, // Using server-side pagination
            searching: true,
            info: false,
            order: [[0, 'desc']], // Sort by first column (ID) in descending order
            columnDefs: [
                { 
                    orderable: false, 
                    targets: [8] // Disable sorting on actions column
                },
                { 
                    className: "dt-center", 
                    targets: [0, 3, 4, 5, 6, 7, 8] // Center align these columns
                }
            ]
        });
    }

    // Handle status update
    $(document).on('click', '.status-update', function(e) {
        e.preventDefault();
        
        const orderId = $(this).data('id');
        const newStatus = $(this).data('status');
        
        if (confirm('Anda yakin ingin mengubah status pesanan ini?')) {
            $.ajax({
                url: '<?= site_url('admin/orders/update-status/') ?>' + orderId,
                type: 'POST',
                data: { 
                    status: newStatus,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Terjadi kesalahan saat memperbarui status');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>