<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Customer Header -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Customer Detail</h4>
                        <small class="text-muted">ID: #<?= $customer['id'] ?></small>
                    </div>
                    <div>
                        <a href="<?= site_url('admin/customers') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Customers
                        </a>
                        <a href="<?= site_url('admin/customers/edit/' . $customer['id']) ?>" class="btn btn-warning ml-2">
                            <i class="fas fa-edit mr-1"></i> Edit Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Customer Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Full Name:</strong></label>
                                <p><?= esc(getFullName($customer)) ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Email:</strong></label>
                                <p>
                                    <a href="mailto:<?= esc($customer['email']) ?>" class="text-decoration-none">
                                        <?= esc($customer['email']) ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Phone:</strong></label>
                                <p><?= $customer['phone_number'] ?: 'Not provided' ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Customer Since:</strong></label>
                                <p><?= date('M d, Y', strtotime($customer['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Email Notifications:</strong></label>
                                <p>
                                    <span class="badge badge-<?= $customer['email_notifications'] ? 'success' : 'secondary' ?>">
                                        <?= $customer['email_notifications'] ? 'Enabled' : 'Disabled' ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>SMS Notifications:</strong></label>
                                <p>
                                    <span class="badge badge-<?= $customer['sms_notifications'] ? 'info' : 'secondary' ?>">
                                        <?= $customer['sms_notifications'] ? 'Enabled' : 'Disabled' ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Stats & Actions -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:<?= esc($customer['email']) ?>" class="btn btn-info">
                            <i class="fas fa-envelope mr-1"></i> Send Email
                        </a>
                        <?php if (!empty($customer['phone_number'])): ?>
                            <a href="tel:<?= esc($customer['phone_number']) ?>" class="btn btn-success">
                                <i class="fas fa-phone mr-1"></i> Call Customer
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('admin/customers/edit/' . $customer['id']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i> Edit Customer
                        </a>
                        <a href="<?= site_url('admin/customers/delete/' . $customer['id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                            <i class="fas fa-trash mr-1"></i> Delete Customer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Customer Registered</h6>
                                <small class="text-muted"><?= date('M d, Y H:i', strtotime($customer['created_at'])) ?></small>
                            </div>
                        </div>
                        
                        <?php if ($customer['updated_at'] != $customer['created_at']): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Information Updated</h6>
                                    <small class="text-muted"><?= date('M d, Y H:i', strtotime($customer['updated_at'])) ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content h6 {
    font-size: 14px;
    font-weight: 600;
}

.timeline-content small {
    font-size: 12px;
}
</style>

<?php
// Helper function to get full name
function getFullName($user) {
    $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
    return !empty($name) ? $name : 'User #' . $user['id'];
}
?>

<?= $this->endSection() ?>
