<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><?= !empty($is_forced) ? 'Set a New Password' : 'Change Password' ?></h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($is_forced)) : ?>
                        <div class="alert alert-warning" data-force-password-change="1">
                            Your password was reset by admin. Please set a new password to continue.
                            Full access is restored after you save it.
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('success')) : ?>
                        <div class="alert alert-success">
                            <?= session('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('user/change-password') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" name="new_password" id="new_password"
                                   class="form-control <?= (isset($validation) && $validation->hasError('new_password')) ? 'is-invalid' : '' ?>"
                                   placeholder="Min. 6 characters" required minlength="6" maxlength="100">
                            <?php if (isset($validation) && $validation->hasError('new_password')) : ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('new_password') ?>
                                </div>
                            <?php endif; ?>
                            <small class="text-muted">Must not be the default password (P@ssword123).</small>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password"
                                   class="form-control <?= (isset($validation) && $validation->hasError('confirm_password')) ? 'is-invalid' : '' ?>"
                                   placeholder="Retype new password" required>
                            <?php if (isset($validation) && $validation->hasError('confirm_password')) : ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('confirm_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save New Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($is_forced)) : ?>
<script>
// Frontend hook: backend sets `force_password_change` flash + this marker.
// If this app ever becomes a SPA/API client, read the flag from the
// login response and route straight to /user/change-password.
if (document.querySelector('[data-force-password-change]')) {
    history.replaceState(null, '', '<?= site_url('user/change-password') ?>');
}
</script>
<?php endif; ?>
<?= $this->endSection() ?>
