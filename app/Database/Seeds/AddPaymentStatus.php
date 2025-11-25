<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddPaymentStatus extends Seeder
{
    public function run()
    {
        $this->db->query("ALTER TABLE orders ADD COLUMN payment_status VARCHAR(50) NOT NULL DEFAULT 'pending' AFTER status");
        echo "Column payment_status added to orders table\n";
    }
}
