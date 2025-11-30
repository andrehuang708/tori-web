<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOrdersTable extends Migration
{
    public function up()
    {
        // Add payment_method to orders if it doesn't exist
        if (!$this->db->fieldExists('payment_method', 'orders')) {
            $fields = [
                'payment_method' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'customer_phone'
                ],
                'subtotal' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'after' => 'total_amount'
                ]
            ];
            $this->forge->addColumn('orders', $fields);
        }

        // Add timestamps to order_items if they don't exist
        if (!$this->db->fieldExists('created_at', 'order_items')) {
            $fields = [
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'subtotal'
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'created_at'
                ]
            ];
            $this->forge->addColumn('order_items', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'payment_method');
        $this->forge->dropColumn('orders', 'subtotal');
        $this->forge->dropColumn('order_items', 'created_at');
        $this->forge->dropColumn('order_items', 'updated_at');
    }
}