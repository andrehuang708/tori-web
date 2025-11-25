<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use CodeIgniter\Pager\Pager;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $perPage = 12;
    
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }
    
    /**
     * Debug method to check database connection and products
     */
    public function debug()
    {
        // Check database connection
        $db = \Config\Database::connect();
        
        // Get all products with category names
        $builder = $db->table('products');
        $builder->select('products.*, categories.name as category_name');
        $builder->join('categories', 'categories.id = products.category_id', 'left');
        $products = $builder->get()->getResultArray();
        
        // Debug output
        echo '<pre>';
        echo 'Database Connection: ' . ($db->connect() ? 'Success' : 'Failed') . "\n\n";
        echo 'Total Products: ' . count($products) . "\n\n";
        print_r($products);
        echo '</pre>';
        exit;
    }
    
    /**
     * Display a listing of products
     */
    public function index()
    {
        // Get filter parameters
        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        $sort = $this->request->getGet('sort') ?? 'newest';
        $page = (int)($this->request->getGet('page') ?? 1);
        
        // Build conditions
        $conditions = ['products.stock >' => 0];
        
        if ($category) {
            $conditions['products.category_id'] = $category;
        }
        
        if ($search) {
            $conditions['products.name LIKE'] = "%$search%";
        }
        
        // Sorting
        $orderBy = 'products.created_at DESC';
        switch ($sort) {
            case 'price_asc':
                $orderBy = 'products.price ASC';
                break;
            case 'price_desc':
                $orderBy = 'products.price DESC';
                break;
            case 'name_asc':
                $orderBy = 'products.name ASC';
                break;
            case 'name_desc':
                $orderBy = 'products.name DESC';
                break;
            case 'popular':
                $orderBy = 'products.views DESC';
                break;
        }
        
        // Get paginated products
        $products = $this->productModel->select('products.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where($conditions)
            ->orderBy($orderBy)
            ->paginate($this->perPage, 'default', $page);
            
        $pager = $this->productModel->pager;
        
        // Get all categories for filter
        $categories = $this->categoryModel->findAll();
        
        // Get featured products for sidebar
        $featuredProducts = $this->productModel->where('is_featured', 1)
            ->where('stock >', 0)
            ->orderBy('RAND()')
            ->findAll(3);
        
        $data = [
            'title' => 'Our Products',
            'products' => $products,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'pager' => $pager,
            'currentCategory' => $category,
            'searchTerm' => $search,
            'currentSort' => $sort,
            'totalProducts' => $pager->getTotal()
        ];
        
        return view('products/index', $data);
    }
    
    /**
     * Display the specified product
     */
    public function detail($slug = null)
    {
        if (empty($slug)) {
            return redirect()->to('/products');
        }
        
        // Get product with category
        $product = $this->productModel->select('products.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.slug', $slug)
            ->first();
            
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Product not found');
        }
        
        // Increment view count
        $this->productModel->incrementViews($product['id']);
        
        // Get related products
        $relatedProducts = $this->productModel->where('category_id', $product['category_id'])
            ->where('id !=', $product['id'])
            ->where('stock >', 0)
            ->orderBy('RAND()')
            ->findAll(4);
            
        $data = [
            'title' => $product['name'],
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];
        
        return $this->render('products/detail', $data);
    }
    
    /**
     * Search products
     */
    public function search()
    {
        $search = $this->request->getGet('q');
        
        if (empty($search)) {
            return redirect()->to('/products');
        }
        
        $products = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->like('products.name', $search)
            ->orLike('products.description', $search)
            ->orLike('categories.name', $search)
            ->where('products.stock >', 0)
            ->orderBy('products.name', 'ASC')
            ->findAll();
            
        $data = [
            'title' => 'Search Results for: ' . $search,
            'products' => $products,
            'searchTerm' => $search
        ];
        
        return $this->render('products/search', $data);
    }
}
