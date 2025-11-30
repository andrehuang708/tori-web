<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddViewsToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'views' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'comment'    => 'Product view count'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'views');
    }
}
