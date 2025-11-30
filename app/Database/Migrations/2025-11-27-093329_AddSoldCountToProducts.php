<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSoldCountToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'sold_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'Number of items sold'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'sold_count');
    }
}
