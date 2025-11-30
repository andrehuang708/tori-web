<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdatePOPriceSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        
        // Update product 1 (Superhero Action Figure) to have PO price
        $db->table('products')->where('id', 1)->update([
            'po_price_desc' => 75000
        ]);
        
        // Update product 2 (Robot Warrior) to have PO price
        $db->table('products')->where('id', 2)->update([
            'po_price_desc' => 95000
        ]);
        
        echo "Updated PO prices for products 1 and 2\n";
    }
}
