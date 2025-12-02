<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title"><?= $title ?></h4>
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

                    <!-- Search Form -->
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search customers by name, email, or phone..." value="<?= esc($search) ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <?php if (!empty($search)): ?>
                                    <a href="<?= site_url('admin/customers') ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <!-- Customers Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="customersTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Notifications</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($customers) && !empty($customers)) : ?>
                                    <?php foreach ($customers as $customer) : ?>
                                        <tr>
                                            <td><?= $customer['id'] ?></td>
                                            <td>
                                                <a href="<?= site_url('admin/customers/detail/' . $customer['id']) ?>" class="text-decoration-none">
                                                    <?= esc(getFullName($customer)) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="mailto:<?= esc($customer['email']) ?>" class="text-decoration-none">
                                                    <?= esc($customer['email']) ?>
                                                </a>
                                            </td>
                                            <td><?= $customer['phone_number'] ?? '-' ?></td>
                                            <td>
                                                <span class="badge badge-<?= $customer['email_notifications'] ? 'success' : 'secondary' ?>" title="Email Notifications">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <span class="badge badge-<?= $customer['sms_notifications'] ? 'info' : 'secondary' ?>" title="SMS Notifications">
                                                    <i class="fas fa-sms"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= site_url('admin/customers/detail/' . $customer['id']) ?>" class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= site_url('admin/customers/edit/' . $customer['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= site_url('admin/customers/delete/' . $customer['id']) ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this customer?')"
                                                       title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <?php if (!empty($search)) : ?>
                                                No customers found matching your search criteria.
                                            <?php else : ?>
                                                No customers found.
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($pager) && $pager['total_pages'] > 1): ?>
                        <nav aria-label="Customers pagination">
                            <ul class="pagination justify-content-center">
                                <?php if ($pager['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= site_url('admin/customers?page=' . ($pager['current_page'] - 1) . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $pager['total_pages']; $i++): ?>
                                    <?php if ($i == $pager['current_page']): ?>
                                        <li class="page-item active">
                                            <span class="page-link"><?= $i ?></span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= site_url('admin/customers?page=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($pager['current_page'] < $pager['total_pages']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= site_url('admin/customers?page=' . ($pager['current_page'] + 1) . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>

                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Showing <?= (($pager['current_page'] - 1) * $pager['per_page']) + 1 ?> to 
                                <?= min($pager['current_page'] * $pager['per_page'], $pager['total_items']) ?> 
                                of <?= $pager['total_items'] ?> customers
                            </small>
                        </div>
                    <?php endif; ?>
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
    $('#customersTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']] // Sort by first column (ID) descending
    });
});
</script>
<?= $this->endSection() ?>
