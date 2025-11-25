<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800"><?= isset($category['id']) ? 'Edit Category' : 'Add New Category' ?></h1>
            
            <div class="card shadow mb-4">
                <div class="card-body">
                    <?= form_open('admin/categories/save') ?>
                        <?php if (isset($category['id'])): ?>
                            <input type="hidden" name="id" value="<?= $category['id'] ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= old('name', $category['name'] ?? '') ?>" required>
                                    <?php if (isset($validation['name'])): ?>
                                        <div class="text-danger small mt-1"><?= $validation['name'] ?></div>
                                    <?php endif; ?>
                                    <?php if (session()->has('validation.name')): ?>
                                        <div class="text-danger small mt-1"><?= session('validation.name') ?></div>
                                    <?php endif; ?>
                                    <small class="form-text text-muted">Enter a category name (3-100 characters)</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="slug" name="slug" 
                                           value="<?= old('slug', $category['slug'] ?? '') ?>" required>
                                    <?php if (isset($validation['slug'])): ?>
                                        <div class="text-danger small mt-1"><?= $validation['slug'] ?></div>
                                    <?php endif; ?>
                                    <?php if (session()->has('validation.slug')): ?>
                                        <div class="text-danger small mt-1"><?= session('validation.slug') ?></div>
                                    <?php endif; ?>
                                    <small class="form-text text-muted">URL-friendly version of the name (e.g., "category-name")</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                <?= isset($category['id']) ? 'Update Category' : 'Save Category' ?>
                            </button>
                            <a href="<?= site_url('admin/categories') ?>" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Auto-generate slug from name
    $('#name').on('input', function() {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-'); // Replace multiple hyphens with single
        $('#slug').val(slug);
    });
});
</script>
<?= $this->endSection() ?>
