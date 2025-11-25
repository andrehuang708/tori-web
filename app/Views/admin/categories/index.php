<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Categories Management</h4>
                    <a href="<?= site_url('admin/categories/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Category
                    </a>
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
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="categoriesTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Products Count</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light d-flex align-items-center justify-content-center mr-3" 
                                                         style="width: 50px; height: 50px; border-radius: 4px;">
                                                        <i class="fas fa-tag text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($category['name']) ?></h6>
                                                        <small class="text-muted">Slug: <?= $category['slug'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= ($category['product_count'] ?? 0) > 0 ? 'info' : 'secondary' ?>">
                                                    <?= $category['product_count'] ?? 0 ?> products
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($category['created_at'])): ?>
                                                    <small><?= date('M d, Y', strtotime($category['created_at'])) ?></small><br>
                                                    <small class="text-muted"><?= date('h:i A', strtotime($category['created_at'])) ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">N/A</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('admin/categories/edit/' . $category['id']) ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Edit Category">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= site_url('admin/categories/delete/' . $category['id']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   title="Delete Category"
                                                   onclick="return confirm('Are you sure you want to delete this category?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="py-3">
                                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No categories found</p>
                                                <small class="text-muted">Create your first category to get started</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#categoriesTable').DataTable({
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        },
        pageLength: 25,
        order: [[0, 'asc']]
    });
});
</script>
<?= $this->endSection() ?>
