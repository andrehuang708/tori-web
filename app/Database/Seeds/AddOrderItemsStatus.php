<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddOrderItemsStatus extends Seeder
{
    public function run()
    {
        $this->db->query("ALTER TABLE order_items ADD COLUMN order_items_status VARCHAR(50) NOT NULL DEFAULT 'not fulfilled' AFTER updated_at");
        echo "Column order_items_status added to order_items table\n";
    }
}
