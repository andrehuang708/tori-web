<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['category_id', 'name', 'slug', 'sku', 'description', 'price', 'stock', 'image'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'category_id' => 'required|is_natural_no_zero',
        'name' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|min_length[3]|max_length[255]|is_unique[products.slug,id,{id}]',
        'sku' => 'permit_empty|min_length[3]|max_length[100]|is_unique[products.sku,id,{id}]',
        'description' => 'permit_empty',
        'price' => 'required|numeric|greater_than_equal_to[0]',
        'stock' => 'required|integer|greater_than_equal_to[0]',
        'image' => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'category_id' => [
            'required' => 'Category is required',
            'is_natural_no_zero' => 'Please select a valid category'
        ],
        'name' => [
            'required' => 'Product name is required',
            'min_length' => 'Product name must be at least 3 characters',
            'max_length' => 'Product name cannot exceed 255 characters'
        ],
        'slug' => [
            'required' => 'Slug is required',
            'min_length' => 'Slug must be at least 3 characters',
            'max_length' => 'Slug cannot exceed 255 characters',
            'is_unique' => 'This slug is already in use'
        ],
        'sku' => [
            'min_length' => 'SKU must be at least 3 characters',
            'max_length' => 'SKU cannot exceed 100 characters',
            'is_unique' => 'This SKU is already in use'
        ],
        'price' => [
            'required' => 'Price is required',
            'numeric' => 'Price must be a number',
            'greater_than_equal_to' => 'Price cannot be negative'
        ],
        'stock' => [
            'required' => 'Stock is required',
            'integer' => 'Stock must be a whole number',
            'greater_than_equal_to' => 'Stock cannot be negative'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name'])) {
            $data['data']['slug'] = url_title(strtolower($data['data']['name']), '-', true);
        }
        return $data;
    }

    // Get products with category name
    public function getProductsWithCategory($where = [], $limit = null, $offset = 0)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('p.*, c.name as category_name');
        $builder->join('categories c', 'c.id = p.category_id', 'left');
        
        if (!empty($where)) {
            $builder->where($where);
        }
        
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        $builder->orderBy('p.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    // Get product by slug with category
    public function getProductBySlug($slug)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('p.*, c.name as category_name');
        $builder->join('categories c', 'c.id = p.category_id', 'left');
        $builder->where('p.slug', $slug);
        return $builder->get()->getRowArray();
    }

    // Get related products
    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        return $this->where('id !=', $productId)
                   ->where('category_id', $categoryId)
                   ->where('stock >', 0)
                   ->orderBy('RAND()')
                   ->findAll($limit);
    }

    // Update product stock
    public function updateStock($productId, $quantity, $operation = 'decrease')
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        $newStock = $operation === 'decrease' 
            ? $product['stock'] - $quantity 
            : $product['stock'] + $quantity;

        if ($newStock < 0) {
            return false;
        }

        return $this->update($productId, ['stock' => $newStock]);
    }
    
    public function getProductsForDatatable($start, $length, $search, $order)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id', 'left');
        
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('products.name', $search)
                   ->orLike('products.description', $search)
                   ->orLike('categories.name', $search)
                   ->groupEnd();
        }
        
        // Order by
        $columns = ['products.name', 'products.price', 'products.stock', 'categories.name'];
        $builder->orderBy($columns[$order['column']], $order['dir']);
        
        // Limit and offset
        $builder->limit($length, $start);
        
        $result = $builder->get()->getResultArray();
        log_message('debug', 'getProductsForDatatable: Found ' . count($result) . ' products');
        return $result;
    }

    public function countFiltered($search)
    {
        $builder = $this->select('products.*, categories.name as category_name')
                       ->join('categories', 'categories.id = products.category_id', 'left');
        
        if (!empty($search)) {
            $builder->groupStart()
                   ->like('products.name', $search)
                   ->orLike('products.description', $search)
                   ->orLike('categories.name', $search)
                   ->groupEnd();
        }
        
        $result = $builder->countAllResults();
        log_message('debug', 'countFiltered: Found ' . $result . ' filtered products');
        return $result;
    }
}
