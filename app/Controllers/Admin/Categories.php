<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Categories extends BaseController
{
    protected $categoryModel;
    protected $perPage = 15;
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->categoryModel = new CategoryModel();
    }
    
    /**
     * List all categories
     */
    public function index($page = 1)
    {
        $categories = $this->categoryModel->getCategoriesWithCount();
        $totalCategories = $this->categoryModel->countAllResults();
        
        $data = [
            'categories' => $categories,
            'pager' => [
                'current_page' => (int) $page,
                'total_items' => $totalCategories,
                'per_page' => $this->perPage,
                'total_pages' => ceil($totalCategories / $this->perPage)
            ]
        ];
        
        return view('admin/categories/index', $data);
    }
    
    /**
     * Add new category
     */
    public function create()
    {
        return view('admin/categories/form');
    }
    
    /**
     * Edit existing category
     */
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/categories')->with('error', 'Category ID is required');
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found');
        }
        
        $data = [
            'category' => $category
        ];
        
        return view('admin/categories/form', $data);
    }
    
    /**
     * Save category
     */
    public function save()
    {
        log_message('debug', 'Categories::save() - Method called');
        
        $id = $this->request->getPost('id');
        log_message('debug', 'Categories::save() - ID: ' . ($id ?? 'null'));
        
        // Get all POST data for debugging
        $postData = $this->request->getPost();
        log_message('debug', 'Categories::save() - POST data: ' . json_encode($postData));
        
        // Validation rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'slug' => 'required|min_length[3]|max_length[100]|is_unique[categories.slug,id,{id}]',
        ];
        
        log_message('debug', 'Categories::save() - Validation rules set');
        
        if ($this->validate($rules)) {
            log_message('debug', 'Categories::save() - Validation passed');
            
            $data = [
                'name' => $this->request->getPost('name'),
                'slug' => $this->request->getPost('slug'),
            ];
            
            log_message('debug', 'Categories::save() - Data prepared: ' . json_encode($data));
            
            log_message('debug', 'Categories::save() - Final data: ' . json_encode($data));
            
            // Save category
            if (empty($id)) {
                log_message('debug', 'Categories::save() - Inserting new category');
                $result = $this->categoryModel->insert($data);
                log_message('debug', 'Categories::save() - Insert result: ' . $result);
                $message = 'Category added successfully';
            } else {
                log_message('debug', 'Categories::save() - Updating category ID: ' . $id);
                $result = $this->categoryModel->update($id, $data);
                log_message('debug', 'Categories::save() - Update result: ' . $result);
                $message = 'Category updated successfully';
            }
            
            log_message('debug', 'Categories::save() - Redirecting with success');
            return redirect()->to('/admin/categories')->with('success', $message);
        } else {
            log_message('debug', 'Categories::save() - Validation failed');
            $errors = $this->validator->getErrors();
            log_message('debug', 'Categories::save() - Validation errors: ' . json_encode($errors));
            
            // Set validation errors to session
            session()->setFlashdata('validation', $errors);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Delete category
     */
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/categories')->with('error', 'Category ID is required');
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found');
        }
        
        // Check if category has products
        $productModel = new \App\Models\ProductModel();
        $hasProducts = $productModel->where('category_id', $id)->countAllResults() > 0;
        
        if ($hasProducts) {
            return redirect()->to('/admin/categories')->with('error', 'Cannot delete category with existing products');
        }
        
        // Delete category image if exists
        if (!empty($category['image'])) {
            $imagePath = ROOTPATH . 'public/' . $category['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Delete category
        $this->categoryModel->delete($id);
        
        return redirect()->to('/admin/categories')->with('success', 'Category deleted successfully');
    }
}
