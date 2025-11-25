<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSkuToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'id',
            ]
        ];
        $this->forge->addColumn('products', $fields);
        $this->forge->addKey('sku', false, true); // Add unique index on sku
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'sku');
    }
}
