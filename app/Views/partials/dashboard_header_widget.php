<!-- Dashboard Header Widget -->
<div class="dashboard-header-widget">
    <div class="dropdown">
        <a href="#" class="nav-link" data-toggle="dropdown" title="My Account Dashboard">
            <i class="fas fa-tachometer-alt"></i>
            <span class="badge badge-success navbar-badge"><?= $total_orders ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 320px;">
            <div class="dropdown-header">
                <i class="fas fa-user-circle mr-2"></i>
                <?= session()->get('user_name') ?>'s Dashboard
            </div>
            <div class="dropdown-divider"></div>
            
            <!-- Quick Stats -->
            <div class="px-3 py-2">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-primary">
                            <strong><?= $total_orders ?></strong>
                            <div class="small text-muted">Orders</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-warning">
                            <strong><?= $pending_orders ?></strong>
                            <div class="small text-muted">Pending</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-success">
                            <strong><?= $completed_orders ?></strong>
                            <div class="small text-muted">Completed</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dropdown-divider"></div>
            
            <!-- Recent Orders -->
            <?php if (!empty($recent_orders)): ?>
                <div class="px-3 py-2">
                    <div class="small text-muted mb-2">Recent Orders:</div>
                    <?php foreach (array_slice($recent_orders, 0, 3) as $order): ?>
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <div>
                                <div class="small font-weight-bold"><?= esc($order['order_number']) ?></div>
                                <div class="small text-muted"><?= date('M d', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-<?= getOrderStatusColor($order['status']) ?>"><?= ucfirst($order['status']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="dropdown-divider"></div>
            <?php endif; ?>
            
            <!-- Action Links -->
            <a href="<?= site_url('user/dashboard') ?>" class="dropdown-item">
                <i class="fas fa-tachometer-alt mr-2"></i> Full Dashboard
            </a>
            <a href="<?= site_url('user/order-history') ?>" class="dropdown-item">
                <i class="fas fa-list mr-2"></i> Order History
            </a>
            <a href="<?= site_url('user/track-order') ?>" class="dropdown-item">
                <i class="fas fa-search mr-2"></i> Track Order
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= site_url('logout') ?>" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
    </div>
</div>

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
