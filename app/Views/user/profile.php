<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -20px -15px 20px -15px;
    }
    .profile-form {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 2rem;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .sidebar {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    .avatar-upload {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    .avatar-upload .preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 4px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #6c757d;
    }
    .avatar-upload .upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #007bff;
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        border: 3px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .avatar-upload .upload-btn:hover {
        background: #0056b3;
        transform: scale(1.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <div class="p-3">
                    <h6 class="text-muted mb-3">Account Menu</h6>
                    <nav class="nav nav-pills flex-column">
                        <a href="<?= site_url('user/order-history') ?>" class="nav-link">
                            <i class="fas fa-list me-2"></i>Order History
                        </a>
                        <a href="<?= site_url('user/track-order') ?>" class="nav-link">
                            <i class="fas fa-search me-2"></i>Track Order
                        </a>
                        <a href="<?= site_url('user/profile') ?>" class="nav-link active">
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
                        <hr>
                        
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Profile Form -->
            <div class="profile-form">
                <div class="text-center mb-4">
                    <div class="avatar-upload">
                        <div class="preview">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="upload-btn">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <h3 class="mt-3">Profile Settings</h3>
                    <p class="text-muted">Manage your personal information and preferences</p>
                </div>

                <form method="post" action="<?= site_url('user/profile/update') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" class="form-control" id="email" 
                                   name="email" value="<?= esc($user['email']) ?>" readonly>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">
                                <i class="fas fa-phone me-2"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control" id="phone_number" 
                                   name="phone_number" value="<?= esc($user['phone_number']) ?>" readonly>
                            <small class="text-muted">Phone cannot be changed</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">
                                <i class="fas fa-user me-2"></i>First Name
                            </label>
                            <input type="text" class="form-control" id="first_name" 
                                   name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" 
                                   placeholder="Enter your first name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">
                                <i class="fas fa-user me-2"></i>Last Name
                            </label>
                            <input type="text" class="form-control" id="last_name" 
                                   name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" 
                                   placeholder="Enter your last name">
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </h5>
                    <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key me-2"></i>Current Password
                            </label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" placeholder="Enter current password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key me-2"></i>New Password
                            </label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" placeholder="Enter new password">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-key me-2"></i>Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" placeholder="Confirm new password">
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">
                        <i class="fas fa-bell me-2"></i>Notification Preferences
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications" 
                                       name="email_notifications" <?= ($user['email_notifications'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="email_notifications">
                                    <i class="fas fa-envelope me-2"></i>Email Notifications
                                </label>
                                <small class="text-muted d-block">Receive order updates via email</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sms_notifications" 
                                       name="sms_notifications" <?= ($user['sms_notifications'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="sms_notifications">
                                    <i class="fas fa-sms me-2"></i>SMS Notifications
                                </label>
                                <small class="text-muted d-block">Receive order updates via SMS</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <a href="<?= site_url('user/dashboard') ?>" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>

                <!-- Account Statistics -->
                <div class="row mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-calendar fa-2x text-primary mb-3"></i>
                                <h6 class="card-title">Member Since</h6>
                                <p class="card-text"><?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-shield-alt fa-2x text-success mb-3"></i>
                                <h6 class="card-title">Account Status</h6>
                                <p class="card-text">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-cog fa-2x text-warning mb-3"></i>
                                <h6 class="card-title">Settings</h6>
                                <p class="card-text">Customized</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    // Show password strength indicator
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;
        
        // You can add a password strength indicator here if needed
    });
</script>
<?= $this->endSection() ?>
