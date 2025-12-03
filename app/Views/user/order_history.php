<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">

<style>
    body {
        background-color: #f8f9fa;
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -20px -15px 20px -15px;
    }
    .sidebar {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    /* DataTable custom styles */
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem;
        margin-left: 0.25rem;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #007bff;
        color: white !important;
        border: 1px solid #007bff;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        color: #007bff !important;
    }
    .dataTables_wrapper .dataTables_info {
        clear: both;
        float: left;
        padding-top: 0.75rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        text-align: right;
        padding-top: 0.25rem;
    }
    .nav-link {
        color: #6c757d;
        border-radius: 8px;
        margin: 0.25rem 0;
    }
    .nav-link:hover, .nav-link.active {
        background-color: #f8f9fa;
        color: #007bff;
    }
    .nav-link i {
        width: 20px;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
    }
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    .filter-section {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-3">
            <div class="d-md-none mb-3">
                <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#accountMenuCollapse" aria-expanded="false" aria-controls="accountMenuCollapse">
                    <i class="fas fa-bars me-2"></i> Account Menu
                </button>
            </div>
            
            <div class="collapse d-md-block" id="accountMenuCollapse">
                <div class="sidebar">
                    <div class="p-3">
                        <h6 class="text-muted mb-3 d-none d-md-block">Account Menu</h6>
                        <nav class="nav nav-pills flex-column">
                            <a href="<?= site_url('user/order-history') ?>" class="nav-link active">
                                <i class="fas fa-list me-2"></i>Order History
                            </a>
                            <a href="<?= site_url('user/track-order') ?>" class="nav-link">
                                <i class="fas fa-search me-2"></i>Track Order
                            </a>
                            <a href="<?= site_url('user/profile') ?>" class="nav-link">
                                <i class="fas fa-user me-2"></i>Profile Settings
                            </a>
                            <a href="<?= site_url('cart') ?>" class="nav-link">
                                <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                            </a>
                            <a href="<?= site_url('/') ?>" class="nav-link">
                                <i class="fas fa-home me-2"></i>Back to Shop
                            </a>
                            <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Log Out
                            </a>
                            <hr class="d-none d-md-block">
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Filter Section -->
            

            <!-- Orders Table -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                            <h4>No Orders Found</h4>
                            <p class="text-muted">You haven't placed any orders yet.</p>
                            <a href="<?= site_url('/') ?>" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="ordersTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th data-priority="1">Order #</th>
                                        <th data-priority="2">Date</th>
                                        <th data-priority="4">Items</th>
                                        <th data-priority="3">Status</th>
                                        <th data-priority="5">Total</th>
                                        <th data-priority="6">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): 
                                        $statusColor = getOrderStatusColor($order['status']);
                                    ?>
                                        <tr>
                                            <td class="font-weight-bold">#<?= esc($order['order_number']) ?></td>
                                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                            <td><?= count($order['items']) ?> item<?= count($order['items']) > 1 ? 's' : '' ?></td>
                                            <td>
                                                <span class="badge bg-<?= $statusColor ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td class="font-weight-bold">Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td>
                                            <td>
                                                <a href="<?= site_url('user/order/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
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

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#ordersTable').DataTable({
        responsive: true,
        order: [[0, 'desc']], // Sort by order ID (1st column) in descending order
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        processing: true,
        language: {
            search: "Search orders:",
            searchPlaceholder: "Order #, status, etc...",
            lengthMenu: "Show _MENU_ orders",
            zeroRecords: "No matching orders found",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            infoEmpty: "No orders available",
            infoFiltered: "(filtered from _MAX_ total orders)",
            paginate: {
                first: '«',
                previous: '‹',
                next: '›',
                last: '»'
            }
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        initComplete: function() {
            // Style search box
            $('.dataTables_filter input')
                .addClass('form-control form-control-sm')
                .attr('placeholder', 'Search orders...')
                .css('width', '250px');
                
            // Style length menu
            $('.dataTables_length select').addClass('form-select form-select-sm');
            
            // Add custom class to info text
            $('.dataTables_info').addClass('text-muted');
        }
    });
    
    // Style pagination buttons
    function stylePagination() {
        $('.paginate_button')
            .removeClass('btn-primary btn-secondary')
            .addClass('btn-sm btn-outline-secondary mx-1');
            
        $('.paginate_button.current')
            .removeClass('btn-outline-secondary')
            .addClass('btn-primary');
    }
    
    // Apply styles on initial load and after each draw
    stylePagination();
    table.on('draw', stylePagination);
    
    // Add margin to search box
    $('.dataTables_filter').css('margin-bottom', '1rem');
    
    // Fix responsive child row display
    table.on('responsive-display', function(e, datatable, row, showHide, update) {
        if (showHide) {
            $('div.dtr-data', row.child()).slideDown('fast');
        } else {
            $('div.dtr-data', row.child()).slideUp('fast');
        }
    });
});

// Add custom styles for responsive display
$('<style>' +
    '.dtr-data { padding: 0px; }' +
    '.dtr-data table { width: 100%; }' +
    '.dtr-title { font-weight: bold; min-width: 100px; display: inline-block; }' +
    '.dtr-data::before { display: none; }' +
    '.dtr-details { width: 100%; }' +
    '.dtr-details tr { border-bottom: 1px solid #eee; }' +
    '.dtr-details td { padding: 8px 5px; }' +
    '.dtr-details td:first-child { font-weight: bold; }' +
    '.dtr-bs-modal .modal-dialog { margin: 0.5rem; }' +
    '.dtr-bs-modal .modal-body { padding: 15px; }' +
    '.dtr-bs-modal .modal-header { padding: 10px 15px; }' +
    '.dtr-bs-modal .close { margin: -1rem -1rem -1rem auto; }' +
'</style>').appendTo('head');
</script>
</script>
<?= $this->endSection() ?>

<?php
// Helper function for status colors
function getOrderStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
