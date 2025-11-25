<?= $this->extend('admin/layouts/auth') ?>

<?= $this->section('content') ?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= site_url() ?>"><?= getenv('site_name') ?> <b>Admin</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

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

            <?= form_open('admin/forgot-password', ['method' => 'post']) ?>
                <?= csrf_field() ?>
                
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                           placeholder="Email" value="<?= old('email') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <?php if (isset($validation) && $validation->hasError('email')) : ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('email') ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            Request New Password
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
