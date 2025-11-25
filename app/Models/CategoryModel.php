<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'slug'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'slug' => 'required|min_length[3]|max_length[100]|is_unique[categories.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 3 characters',
            'max_length' => 'Category name cannot exceed 100 characters'
        ],
        'slug' => [
            'required' => 'Slug is required',
            'min_length' => 'Slug must be at least 3 characters',
            'max_length' => 'Slug cannot exceed 100 characters',
            'is_unique' => 'This slug is already in use'
        ]
    ];

    // Callbacks - removed since we handle slug manually
    // protected $beforeInsert = ['generateSlug'];
    // protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        if (isset($data['data']['name'])) {
            $data['data']['slug'] = url_title(strtolower($data['data']['name']), '-', true);
        }
        return $data;
    }

    // Get all categories with product count
    public function getCategoriesWithCount()
    {
        $builder = $this->db->table($this->table . ' c');
        $builder->select('c.*, COUNT(p.id) as product_count');
        $builder->join('products p', 'p.category_id = c.id', 'left');
        $builder->groupBy('c.id');
        return $builder->get()->getResultArray();
    }
}
