<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyData extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        
        // Insert categories
        $categories = [
            ['name' => 'Pakaian', 'slug' => 'pakaian'],
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Kosmetik', 'slug' => 'kosmetik'],
            ['name' => 'Makanan', 'slug' => 'makanan'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris']
        ];
        $this->db->table('categories')->insertBatch($categories);
        $categoryIds = $this->db->insertID();

        // Insert products
        $products = [];
        for ($i = 1; $i <= 20; $i++) {
            $products[] = [
                'category_id' => $faker->numberBetween(1, 5),
                'name' => 'Produk ' . $i,
                'slug' => 'produk-' . $i,
                'description' => $faker->sentence(15),
                'price' => $faker->numberBetween(10000, 1000000),
                'stock' => $faker->numberBetween(5, 100),
                'image' => 'default.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        $this->db->table('products')->insertBatch($products);
        $productIds = $this->db->insertID();

        // Insert admin user if not exists
        $adminExists = $this->db->table('admins')
            ->where('email', 'admin@example.com')
            ->countAllResults() > 0;

        if (!$adminExists) {
            $this->db->table('admins')->insert([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'super_admin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Insert sample orders
        for ($i = 1; $i <= 5; $i++) {
            $orderNumber = 'INV' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT);
            $orderData = [
                'order_number' => $orderNumber,
                'customer_name' => $faker->name,
                'customer_email' => $faker->email,
                'customer_phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'total_amount' => 0, // Will be updated
                'payment_method' => $faker->randomElement(['bank_transfer', 'cod', 'e_wallet']),
                'status' => $faker->randomElement(['pending', 'processing', 'completed']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('orders')->insert($orderData);
            $orderId = $this->db->insertID();
            
            // Insert order items
            $orderTotal = 0;
            $numItems = $faker->numberBetween(1, 5);
            
            for ($j = 1; $j <= $numItems; $j++) {
                $productId = $faker->numberBetween(1, 20);
                $quantity = $faker->numberBetween(1, 5);
                
                $product = $this->db->table('products')
                    ->where('id', $productId)
                    ->get()
                    ->getRowArray();
                
                if ($product) {
                    $subtotal = $product['price'] * $quantity;
                    $orderTotal += $subtotal;
                    
                    $this->db->table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_id' => $productId,
                        'product_name' => $product['name'],
                        'quantity' => $quantity,
                        'price' => $product['price'],
                        'subtotal' => $subtotal,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            
            // Update order total
            $this->db->table('orders')
                ->where('id', $orderId)
                ->update(['total_amount' => $orderTotal]);
        }
    }
}