<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categoryModel = new \App\Models\CategoryModel();
        
        // Dummy categories
        $categories = [
            [
                'name' => 'Action Figures',
                'slug' => 'action-figures',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Educational Toys',
                'slug' => 'educational-toys',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Building Blocks',
                'slug' => 'building-blocks',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Dolls & Plushies',
                'slug' => 'dolls-plushies',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Vehicles & Remote Control',
                'slug' => 'vehicles-remote-control',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Board Games & Puzzles',
                'slug' => 'board-games-puzzles',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Arts & Crafts',
                'slug' => 'arts-crafts',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Outdoor & Sports',
                'slug' => 'outdoor-sports',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Electronic Toys',
                'slug' => 'electronic-toys',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Musical Instruments',
                'slug' => 'musical-instruments',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($categories as $category) {
            $categoryModel->insert($category);
        }

        echo "CategorySeeder: Inserted " . count($categories) . " categories\n";
    }
}
