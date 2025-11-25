<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOrderItemsStatusToOrderItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('order_items', [
            'order_items_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'not fulfilled',
                'null' => false,
                'after' => 'updated_at'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('order_items', 'order_items_status');
    }
}
