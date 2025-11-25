<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEcommerceTables extends Migration
{
   // In 2024-06-07-000001_CreateEcommerceTables.php
   
   public function up()
   {
       // Categories Table
       $this->forge->addField([
           'id' => [
               'type'           => 'INT',
               'constraint'     => 11,
               'unsigned'       => true,
               'auto_increment' => true,
           ],
           'name' => [
               'type'       => 'VARCHAR',
               'constraint' => '100',
           ],
           'slug' => [
               'type'       => 'VARCHAR',
               'constraint' => '100',
           ],
           'created_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
           'updated_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
       ]);
       $this->forge->addKey('id', true);
       $this->forge->createTable('categories');
   
       // Products Table
       $this->forge->addField([
           'id' => [
               'type'           => 'INT',
               'constraint'     => 11,
               'unsigned'       => true,
               'auto_increment' => true,
           ],
           'category_id' => [
               'type'       => 'INT',
               'constraint' => 11,
               'unsigned'   => true,
           ],
           'name' => [
               'type'       => 'VARCHAR',
               'constraint' => '255',
           ],
           'slug' => [
               'type'       => 'VARCHAR',
               'constraint' => '255',
           ],
           'description' => [
               'type' => 'TEXT',
               'null' => true,
           ],
           'price' => [
               'type' => 'DECIMAL',
               'constraint' => '10,2',
           ],
           'stock' => [
               'type'       => 'INT',
               'constraint' => 11,
               'default'    => 0,
           ],
           'is_featured' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'after'      => 'stock',
                    'comment'    => '1 = Featured, 0 = Not Featured'
            ],
           'image' => [
               'type'       => 'VARCHAR',
               'constraint' => '255',
               'null'       => true,
           ],
           'created_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
           'updated_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
       ]);
       $this->forge->addKey('id', true);
       // Removed foreign key constraint temporarily
       // $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
       $this->forge->createTable('products');
   
       // Orders Table
       $this->forge->addField([
           'id' => [
               'type'           => 'INT',
               'constraint'     => 11,
               'unsigned'       => true,
               'auto_increment' => true,
           ],
           'order_number' => [
               'type'       => 'VARCHAR',
               'constraint' => '50',
           ],
           'customer_name' => [
               'type'       => 'VARCHAR',
               'constraint' => '100',
           ],
           'customer_email' => [
               'type'       => 'VARCHAR',
               'constraint' => '100',
           ],
           'customer_phone' => [
               'type'       => 'VARCHAR',
               'constraint' => '20',
               'null'       => true,
           ],
           'address' => [
               'type' => 'TEXT',
               'null' => true,
           ],
           'total_amount' => [
               'type' => 'DECIMAL',
               'constraint' => '10,2',
           ],
           'status' => [
               'type'       => 'ENUM',
               'constraint' => ['pending', 'processing', 'completed', 'cancelled'],
               'default'    => 'pending',
           ],
           'created_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
           'updated_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
       ]);
       $this->forge->addKey('id', true);
       $this->forge->addKey('order_number');
       $this->forge->createTable('orders');
   
       // Order Items Table
       $this->forge->addField([
           'id' => [
               'type'           => 'INT',
               'constraint'     => 11,
               'unsigned'       => true,
               'auto_increment' => true,
           ],
           'order_id' => [
               'type'       => 'INT',
               'constraint' => 11,
               'unsigned'   => true,
           ],
           'product_id' => [
               'type'       => 'INT',
               'constraint' => 11,
               'unsigned'   => true,
           ],
           'product_name' => [
               'type'       => 'VARCHAR',
               'constraint' => '255',
           ],
           'quantity' => [
               'type'       => 'INT',
               'constraint' => 11,
           ],
           'price' => [
               'type' => 'DECIMAL',
               'constraint' => '10,2',
           ],
           'subtotal' => [
               'type' => 'DECIMAL',
               'constraint' => '10,2',
           ],
           'created_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
           'updated_at' => [
               'type' => 'DATETIME',
               'null' => true,
           ],
       ]);
       $this->forge->addKey('id', true);
       // Removed foreign key constraints temporarily
       // $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
       // $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'SET NULL');
       $this->forge->createTable('order_items');
   }
   
   public function down()
   {
       $this->forge->dropTable('order_items', true);
       $this->forge->dropTable('orders', true);
       $this->forge->dropTable('products', true);
       $this->forge->dropTable('categories', true);
   }
}
