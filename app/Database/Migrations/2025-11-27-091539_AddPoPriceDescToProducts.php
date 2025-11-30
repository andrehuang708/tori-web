<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPoPriceDescToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'po_price_desc' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => null,
                'comment'    => 'PO Price Description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'po_price_desc');
    }
}
