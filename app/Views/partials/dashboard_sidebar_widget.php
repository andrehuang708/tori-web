<!-- Dashboard Sidebar Widget -->
<div class="card dashboard-widget mb-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">
            <i class="fas fa-user-circle mr-2"></i>
            <?= session()->get('user_name') ?>'s Account
        </h6>
    </div>
    <div class="card-body p-3">
        <!-- Quick Stats -->
        <div class="row text-center mb-3">
            <div class="col-6">
                <div class="text-primary">
                    <h5 class="mb-0"><?= $total_orders ?></h5>
                    <small class="text-muted">Total Orders</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-success">
                    <h5 class="mb-0"><?= $total_spent ?></h5>
                    <small class="text-muted">Total Spent</small>
                </div>
            </div>
        </div>
        
        <!-- Status Overview -->
        <?php if ($pending_orders > 0): ?>
            <div class="alert alert-warning py-2 mb-2">
                <i class="fas fa-clock mr-2"></i>
                <?= $pending_orders ?> pending order(s)
            </div>
        <?php endif; ?>
        
        <?php if ($completed_orders > 0): ?>
            <div class="alert alert-success py-2 mb-2">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $completed_orders ?> completed order(s)
            </div>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="d-grid gap-2">
            <a href="<?= site_url('user/dashboard') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
            <a href="<?= site_url('user/order-history') ?>" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-list mr-1"></i> Order History
            </a>
            <a href="<?= site_url('user/track-order') ?>" class="btn btn-outline-info btn-sm">
                <i class="fas fa-search mr-1"></i> Track Order
            </a>
        </div>
    </div>
</div>
