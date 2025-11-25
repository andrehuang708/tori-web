<?= $this->extend('admin/layouts/auth') ?>

<?= $this->section('content') ?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= site_url() ?>"><?= getenv('site_name') ?> <b>Admin</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

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

            <?= form_open('admin/reset-password', ['method' => 'post']) ?>
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= $token ?? '' ?>">
                
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                           placeholder="New Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <?php if (isset($validation) && $validation->hasError('password')) : ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('password') ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="input-group mb-3">
                    <input type="password" name="password_confirm" class="form-control <?= (isset($validation) && $validation->hasError('password_confirm')) ? 'is-invalid' : '' ?>" 
                           placeholder="Confirm New Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <?php if (isset($validation) && $validation->hasError('password_confirm')) : ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('password_confirm') ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            Change Password
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            <?= form_close() ?>

            <p class="mt-3 mb-1">
                <a href="<?= site_url('admin/login') ?>">Back to Login</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<?= $this->endSection() ?>
