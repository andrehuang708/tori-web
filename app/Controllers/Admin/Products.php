<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $perPage = 20; // Number of items per page
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }
    
    /**
     * List all products
     */
    // public function index($page = 1)
    // {
    //     $products = $this->productModel->getProductsWithCategory([], $this->perPage, ($page - 1) * $this->perPage);
    //     $totalProducts = $this->productModel->countAllResults();
        
    //     $data = [
    //         'title' => 'Products Management',
    //         'products' => $products,
    //         'pager' => [
    //             'current_page' => (int) $page,
    //             'total_items' => $totalProducts,
    //             'per_page' => $this->perPage,
    //             'total_pages' => ceil($totalProducts / $this->perPage)
    //         ]
    //     ];
        
    //     return view('admin/products/index', $data);
    // }
    public function index()
{
    // Ensure admin is logged in
    $session = session();
    if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
        return redirect()->to('admin/login')->with('error', 'You must be logged in as admin to access this page.');
    }
    
    // For regular page load, get all products
    $products = $this->productModel->getProductsWithCategory([], 0, 0);
    $totalProducts = $this->productModel->countAllResults();
    
    $data = [
        'title' => 'Daftar Produk',
        'products' => $products,
        'pager' => [
            'current_page' => 1,
            'total_items' => $totalProducts,
            'per_page' => 25,
            'total_pages' => ceil($totalProducts / 25)
        ]
    ];
    
    return view('admin/products/index', $data);
}
    
    /**
     * Add new product
     */
    public function create()
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }
        
        $data = [
            'categories' => $this->categoryModel->findAll()
        ];
        
        return view('admin/products/form', $data);
    }
    
    /**
     * Edit existing product
     */
    public function edit($id = null)
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }
        
        if ($id === null) {
            return redirect()->to('admin/products')->with('error', 'Product ID is required');
        }
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('admin/products')->with('error', 'Product not found');
        }
        
        $data = [
            'product' => $product,
            'categories' => $this->categoryModel->findAll()
        ];
        
        return view('admin/products/form', $data);
    }
    
    /**
     * Save product
     */
    public function save()
    {
        // Ensure admin is logged in
        $session = session();
        if (!$session->has('isAdminLoggedIn') || $session->get('isAdminLoggedIn') !== true) {
            return redirect()->to('admin/login')->with('error', 'You must be logged in as admin to access this page.');
        }
        
        $id = $this->request->getPost('id');
        
        // Validation rules
        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'name' => 'required|min_length[3]|max_length[255]',
            'sku' => 'permit_empty|min_length[3]|max_length[100]|is_unique[products.sku,id,' . ($id ?? '') . ']',
            'description' => 'permit_empty',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'po_price_desc_raw' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'image' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]',
            'product_status' => 'required|in_list[ACTIVE,DEACTIVE]',
        ];
        
        if ($this->validate($rules)) {
            // Get the raw price value from the hidden input
            $price = $this->request->getPost('price_raw') ?: '0';
            
            // Debug logging
            log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));
            log_message('debug', 'PO Price Raw: ' . $this->request->getPost('po_price_desc_raw'));
            log_message('debug', 'PO Price Desc: ' . $this->request->getPost('po_price_desc'));
            
            // Generate SKU if not provided
            $sku = $this->request->getPost('sku');
            if (empty($sku)) {
                // Generate SKU from product name and timestamp
                $name = $this->request->getPost('name');
                $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 4));
                $sku = $prefix . '-' . time();
            }
            
            $data = [
                'category_id' => $this->request->getPost('category_id'),
                'name' => $this->request->getPost('name'),
                'sku' => $sku,
                'description' => $this->request->getPost('description'),
                'price' => $price,
                'stock' => $this->request->getPost('stock'),
                'slug' => url_title($this->request->getPost('name'), '-', true),
                'po_price_desc' => $this->request->getPost('po_price_desc_raw') ?: null,
                'product_status' => $this->request->getPost('product_status') ?: 'ACTIVE'
            ];
            
            // Debug logging for data array
            log_message('debug', 'Data to save: ' . json_encode($data));
            
            // Handle image upload
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $uploadPath = ROOTPATH . '../public_html/uploads/products';
                echo ROOTPATH . '../public_html/uploads/products';
                echo base_url('uploads/products');
                //$uploadPath = base_url('uploads/products');
                log_message('debug', 'path' . $uploadPath);
                // Create uploads directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $image->move($uploadPath, $newName);
                $data['image'] = $newName; // Store only the filename
                
                // Delete old image if exists
                if (!empty($id) && !empty($this->request->getPost('old_image'))) {
                    $oldImage = ROOTPATH . '../public_html/uploads/products/' . $this->request->getPost('old_image');
                    //$oldImage = base_url('uploads/products/'.$this->request->getPost('old_image'));
                     log_message('debug', 'path old' . $oldImage);
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }
            } elseif (!empty($id) && !empty($this->request->getPost('old_image'))) {
                // Make sure we're only storing the filename, not the full path
                $oldImage = $this->request->getPost('old_image');
                $data['image'] = basename($oldImage);
            }
            
            // Save product
            if (empty($id)) {
                try {
                    $result = $this->productModel->insert($data);
                    log_message('debug', 'Insert result: ' . $result);
                    $message = 'Product added successfully';
                } catch (\Exception $e) {
                    log_message('error', 'Insert error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to add product: ' . $e->getMessage());
                }
            } else {
                try {
                    log_message('debug', 'Updating product ID: ' . $id);
                    log_message('debug', 'Update data: ' . json_encode($data));
                    
                    // Check if product exists first
                    $existingProduct = $this->productModel->find($id);
                    if (!$existingProduct) {
                        log_message('error', 'Product not found for update: ' . $id);
                        return redirect()->back()->with('error', 'Product not found');
                    }
                    
                    log_message('debug', 'Existing product data: ' . json_encode($existingProduct));
                    log_message('debug', 'New data to update: ' . json_encode($data));
                    
                    // Check what's actually different
                    $changes = [];
                    foreach ($data as $key => $value) {
                        if (isset($existingProduct[$key]) && $existingProduct[$key] != $value) {
                            $changes[$key] = [
                                'old' => $existingProduct[$key],
                                'new' => $value
                            ];
                        } elseif (!isset($existingProduct[$key])) {
                            $changes[$key] = [
                                'old' => 'NOT SET',
                                'new' => $value
                            ];
                        }
                    }
                    
                    log_message('debug', 'Detected changes: ' . json_encode($changes));
                    
                    $result = $this->productModel->update($id, $data);
                    log_message('debug', 'Update result: ' . var_export($result, true));
                    
                    // If update returns false but we have changes, try direct query
                    if (!$result && !empty($changes)) {
                        log_message('debug', 'Standard update failed, trying direct query');
                        try {
                            $db = \Config\Database::connect();
                            $builder = $db->table('products');
                            $directResult = $builder->where('id', $id)->update($data);
                            log_message('debug', 'Direct query result: ' . var_export($directResult, true));
                            
                            if ($directResult) {
                                $result = true;
                                log_message('debug', 'Direct query succeeded');
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'Direct query error: ' . $e->getMessage());
                        }
                    }
                    
                    // Verify the update actually happened
                    $updatedProduct = $this->productModel->find($id);
                    log_message('debug', 'Updated product PO price: ' . ($updatedProduct['po_price_desc'] ?? 'NULL'));
                    
                    if ($result) {
                        $message = 'Product updated successfully';
                    } else {
                        log_message('error', 'Update returned false but no error thrown');
                        return redirect()->back()->with('error', 'Failed to update product - no changes made');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Update error: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
                }
            }
            
            return redirect()->to('admin/products')->with('success', $message);
        } else {
            // Debug validation errors
            $validation = \Config\Services::validation();
            log_message('debug', 'Validation errors: ' . json_encode($validation->getErrors()));
            
            $this->setValidationErrors();
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Delete product
     */
    public function delete($id = null)
    {
        // Destructive action: POST only (see routes). Reject GET/crawlers.
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('admin/products')->with('error', 'Method not allowed');
        }

        if ($id === null) {
            return redirect()->to('admin/products')->with('error', 'Product ID is required');
        }
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return redirect()->to('admin/products')->with('error', 'Product not found');
        }
        
        // Check if product has orders
        $orderItemModel = new \App\Models\OrderItemModel();
        $hasOrders = $orderItemModel->where('product_id', $id)->countAllResults() > 0;
        
        if ($hasOrders) {
            return redirect()->to('admin/products')->with('error', 'Cannot delete product with existing orders');
        }
        
        // Delete product image if exists
        if (!empty($product['image'])) {
            //$imagePath = ROOTPATH . 'public_html/' . $product['image'];
            $imagePath = base_url('uploads/products/'. $product['image']);
             log_message('debug', 'imagepath' . $$imagePath);
            
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Delete product
        $this->productModel->delete($id);
        
        return redirect()->to('admin/products')->with('success', 'Product deleted successfully');
    }
    
    /**
     * Display a list of products with featured status and filtering
     */
    public function featured()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $productModel = new \App\Models\ProductModel();
        
        // Get filter parameters
        $categoryId = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        
        // Build query
        $products = $productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left');
            
        // Apply category filter
        if (!empty($categoryId)) {
            $products->where('products.category_id', $categoryId);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $products->groupStart()
                ->like('products.name', $search)
                ->orLike('products.description', $search)
                ->orLike('categories.name', $search)
                ->orLike('products.id', $search)
            ->groupEnd();
        }
        
        $data = [
            'title' => 'Manage Featured Products',
            'products' => $products->orderBy('products.name', 'ASC')->findAll(),
            'categories' => $categoryModel->orderBy('name', 'ASC')->findAll(),
            'categoryId' => $categoryId,
            'searchTerm' => $search
        ];
        
        return view('admin/products/featured', $data);
    }
    
    /**
     * Toggle featured status for a product
     */
    public function toggleFeatured($id)
    {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
        
        $newStatus = $product['is_featured'] ? 0 : 1;
        $this->productModel->update($id, ['is_featured' => $newStatus]);
        
        return $this->response->setJSON([
            'success' => true,
            'is_featured' => $newStatus,
            'message' => 'Featured status updated successfully'
        ]);
    }
}
