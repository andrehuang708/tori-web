<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Edit Customer</h4>
                        <small class="text-muted">ID: #<?= $customer['id'] ?></small>
                    </div>
                    <div>
                        <a href="<?= site_url('admin/customers/detail/' . $customer['id']) ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Detail
                        </a>
                        <a href="<?= site_url('admin/customers') ?>" class="btn btn-info ml-2">
                            <i class="fas fa-list mr-1"></i> All Customers
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= site_url('admin/customers/update/' . $customer['id']) ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" 
                                           value="<?= old('first_name', $customer['first_name']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" 
                                           value="<?= old('last_name', $customer['last_name']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" 
                                           value="<?= old('email', $customer['email']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone</label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control" 
                                           value="<?= old('phone_number', $customer['phone_number']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="email_notifications" id="email_notifications" 
                                               class="form-check-input" value="1"
                                               <?= old('email_notifications', $customer['email_notifications']) ? 'checked' : '' ?>>
                                        <label for="email_notifications" class="form-check-label">
                                            <i class="fas fa-envelope"></i> Email Notifications
                                        </label>
                                    </div>
                                    <small class="text-muted">Receive email notifications from the system</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="sms_notifications" id="sms_notifications" 
                                               class="form-check-input" value="1"
                                               <?= old('sms_notifications', $customer['sms_notifications']) ? 'checked' : '' ?>>
                                        <label for="sms_notifications" class="form-check-label">
                                            <i class="fas fa-sms"></i> SMS Notifications
                                        </label>
                                    </div>
                                    <small class="text-muted">Receive SMS notifications from the system</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Customer created on <?= date('M d, Y H:i', strtotime($customer['created_at'])) ?>
                                        <?php if ($customer['updated_at'] != $customer['created_at']): ?>
                                            | Last updated on <?= date('M d, Y H:i', strtotime($customer['updated_at'])) ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Note:</strong> Password cannot be changed from this page for security reasons. 
                                    If you need to reset the customer's password, please use the password reset functionality.
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Update Customer
                            </button>
                            <a href="<?= site_url('admin/customers/detail/' . $customer['id']) ?>" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Helper function to get full name
function getFullName($user) {
    $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
    return !empty($name) ? $name : 'User #' . $user['id'];
}
?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Auto-format phone number
    $('#phone_number').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                value = value;
            } else if (value.length <= 6) {
                value = value.slice(0, 3) + '-' + value.slice(3);
            } else {
                value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
            }
        }
        $(this).val(value);
    });

    // Auto-capitalize first and last name
    $('#first_name, #last_name').on('input', function() {
        var value = $(this).val();
        $(this).val(value.replace(/\b\w/g, function(l) { return l.toUpperCase(); }));
    });

    // Form validation
    $('form').on('submit', function(e) {
        var email = $('#email').val().trim();

        if (email === '') {
            e.preventDefault();
            alert('Please enter email address');
            $('#email').focus();
            return false;
        }

        // Basic email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address');
            $('#email').focus();
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>
