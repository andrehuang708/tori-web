<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    protected $categoryModel;
    protected $productModel;
    protected $perPage = 12;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        // Get featured products with their categories
        $data['featured_products'] = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.stock >', 0)
            ->orderBy('products.created_at', 'DESC')
            ->findAll(8);
        
        // Get categories with product count
        $data['categories'] = $this->categoryModel->select('categories.*, COUNT(products.id) as product_count')
            ->join('products', 'products.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->findAll();
        
        // Get best selling products
        $orderItemModel = new \App\Models\OrderItemModel();
        $data['best_sellers'] = $orderItemModel->getBestSellingProducts(4);
        
        // Get new arrivals
        $data['new_arrivals'] = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.stock >', 0)
            ->orderBy('products.created_at', 'DESC')
            ->findAll(4);
        
        // Get products on sale (example: products with price less than 100)
        $data['sale_products'] = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.stock >', 0)
            ->where('products.price <', 100) // Adjust this condition as needed
            ->orderBy('RAND()')
            ->findAll(4);
        
        return view('home', $data);
    }

    public function products($categorySlug = null, $page = 1)
    {
        $where = [];
        $category = null;
        
        // Filter by category if provided
        if ($categorySlug) {
            $category = $this->categoryModel->where('slug', $categorySlug)->first();
            if (!$category) {
                return $this->redirectWithMessage('/', 'Category not found', 'danger');
            }
            $where['p.category_id'] = $category['id'];
        }
        
        // Get products with pagination
        $products = $this->productModel->getProductsWithCategory($where, $this->perPage, ($page - 1) * $this->perPage);
        $totalProducts = $this->productModel->where($where)->countAllResults();
        
        $data = [
            'products' => $products,
            'category' => $category,
            'pager' => [
                'current_page' => (int) $page,
                'total_items' => $totalProducts,
                'per_page' => $this->perPage,
                'total_pages' => ceil($totalProducts / $this->perPage)
            ]
        ];
        
        return $this->render('products/index', $data);
    }

    public function productDetail($slug)
    {
        $product = $this->productModel->getProductBySlug($slug);
        
        if (!$product) {
            return $this->redirectWithMessage('/', 'Product not found', 'danger');
        }
        
        // Get related products
        $relatedProducts = $this->productModel->getRelatedProducts(
            $product['id'], 
            $product['category_id'], 
            4
        );
        
        $data = [
            'product' => $product,
            'related_products' => $relatedProducts
        ];
        
        return $this->render('products/detail', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $page = (int) $this->request->getGet('page') ?: 1;
        
        if (empty($keyword)) {
            return $this->redirectWithMessage('/', 'Please enter a search term', 'warning');
        }
        
        // Search products
        $builder = $this->productModel->builder();
        $builder->select('products.*, categories.name as category_name')
               ->join('categories', 'categories.id = products.category_id', 'left')
               ->groupStart()
                  ->like('products.name', $keyword)
                  ->orLike('products.description', $keyword)
                  ->orLike('categories.name', $keyword)
               ->groupEnd()
               ->where('products.stock >', 0);
        
        $totalProducts = $builder->countAllResults(false);
        $products = $builder->get($this->perPage, ($page - 1) * $this->perPage)->getResultArray();
        
        $data = [
            'products' => $products,
            'keyword' => $keyword,
            'pager' => [
                'current_page' => $page,
                'total_items' => $totalProducts,
                'per_page' => $this->perPage,
                'total_pages' => ceil($totalProducts / $this->perPage)
            ]
        ];
        
        return $this->render('products/search', $data);
    }

    public function about()
    {
        return $this->render('pages/about');
    }

    public function contact()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|max_length[100]',
                'subject' => 'required|min_length[3]|max_length[200]',
                'message' => 'required|min_length[10]',
                'g-recaptcha-response' => 'required|validateRecaptcha'
            ];
            
            if ($this->validate($rules)) {
                // Process the contact form (e.g., send email)
                $email = \Config\Services::email();
                
                $email->setTo(getenv('admin_email') ?: 'admin@example.com');
                $email->setFrom($this->request->getPost('email'), $this->request->getPost('name'));
                $email->setSubject('Contact Form: ' . $this->request->getPost('subject'));
                $email->setMessage($this->request->getPost('message'));
                
                if ($email->send()) {
                    return $this->redirectWithMessage('contact', 'Your message has been sent successfully!');
                } else {
                    return $this->redirectWithMessage('contact', 'Failed to send message. Please try again.', 'danger');
                }
            } else {
                $this->setValidationErrors();
            }
        }
        
        return $this->render('pages/contact');
    }

    // Add reCAPTCHA validation
    public function validateRecaptcha($str)
    {
        if (empty($str)) {
            $this->validation->setError('recaptcha', 'The reCAPTCHA field is required.');
            return false;
        }
        
        $client = \Config\Services::curlrequest();
        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret'   => getenv('recaptcha_secret_key'),
                'response' => $str,
                'remoteip' => $this->request->getIPAddress()
            ]
        ]);
        
        $result = json_decode($response->getBody(), true);
        
        if (!$result['success']) {
            $this->validation->setError('recaptcha', 'reCAPTCHA validation failed. Please try again.');
            return false;
        }
        
        return true;
    }
}
