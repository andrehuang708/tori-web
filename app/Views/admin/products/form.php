<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800"><?= isset($product) ? 'Edit Product' : 'Add New Product' ?></h1>
            
            <div class="card shadow mb-4">
                <div class="card-body">
                    <?= form_open_multipart('admin/products/save') ?>
                        <?php if (isset($product)): ?>
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <?php if (!empty($product['image'])): ?>
                                <input type="hidden" name="old_image" value="<?= $product['image'] ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= (isset($product) && $product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="<?= old('name', $product['name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="sku">SKU (Leave empty to auto-generate)</label>
                            <input type="text" name="sku" id="sku" class="form-control" 
                                   value="<?= old('sku', $product['sku'] ?? '') ?>">
                            <small class="form-text text-muted">Leave blank to automatically generate a SKU</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?= old('description', $product['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price (IDR)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" name="price" id="price" class="form-control price-input" 
                                       value="<?= old('price', isset($product['price']) ? number_format($product['price'], 0, ',', '.') : '0') ?>" required>
                                <input type="hidden" id="price_raw" name="price_raw">
                            </div>
                            <small class="form-text text-muted">Example: 1.000.000</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control" 
                                   min="0" value="<?= old('stock', $product['stock'] ?? '0') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <?php if (isset($product) && !empty($product['image'])): ?>
                                <div class="mb-2">
                                    <img src="/<?= $product['image'] ?>" alt="Product Image" style="max-width: 200px;" class="img-thumbnail">
                                </div>
                            <?php endif; ?>
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="custom-file-input" 
                                       accept="image/jpeg,image/png,image/gif">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Max size: 2MB. Allowed formats: JPG, PNG, GIF</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <?= isset($product) ? 'Update Product' : 'Add Product' ?>
                            </button>
                            <a href="<?= site_url('admin/products') ?>" class="btn btn-secondary">Cancel</a>
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
    // Update the file input label with the selected file name
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
    
    // Format price input as IDR
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('price');
        const priceRaw = document.getElementById('price_raw');
        
        // Format on page load
        if (priceInput) {
            formatPrice(priceInput);
        }
        
        // Format on input
        priceInput.addEventListener('input', function(e) {
            formatPrice(e.target);
        });
        
        // Format on blur to ensure proper formatting
        priceInput.addEventListener('blur', function(e) {
            formatPrice(e.target, true);
        });
        
        // Format price function
        function formatPrice(input, blur = false) {
            // Remove non-numeric characters
            let value = input.value.replace(/\D/g, '');
            
            // Store raw value in hidden field
            priceRaw.value = value || '0';
            
            // Format with thousands separators
            if (value) {
                value = parseInt(value).toString();
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            
            // Update input value
            input.value = value || '0';
        }
        
        // Handle form submission to remove dots before sending
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const priceInput = document.getElementById('price');
                if (priceInput) {
                    // Remove all non-numeric characters before submission
                    priceInput.value = priceInput.value.replace(/\D/g, '');
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
