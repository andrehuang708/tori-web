<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsFeaturedToProducts extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        
        // Check if the column doesn't exist before adding it
        if (!$this->db->fieldExists('is_featured', 'products')) {
            $fields = [
                'is_featured' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'after'      => 'stock',
                    'comment'    => '1 = Featured, 0 = Not Featured'
                ]
            ];

            $this->forge->addColumn('products', $fields);
        }
        
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        
        // Check if the column exists before dropping it
        if ($this->db->fieldExists('is_featured', 'products')) {
            $this->forge->dropColumn('products', 'is_featured');
        }
        
        $this->db->enableForeignKeyChecks();
    }
}
