<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductStatusToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'product_status' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVE', 'DEACTIVE'],
                'default'    => 'ACTIVE',
                'comment'    => 'Product visibility status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'product_status');
    }
}
