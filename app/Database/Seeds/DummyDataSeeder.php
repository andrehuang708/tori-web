<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        echo "Starting dummy data seeding...\n";
        
        // Run all seeders in order
        $this->call('CategorySeeder');
        $this->call('ProductSeeder');
        $this->call('UserSeeder');
        
        echo "Dummy data seeding completed successfully!\n";
        echo "\nSummary:\n";
        echo "- 10 Categories created\n";
        echo "- 20 Products created\n";
        echo "- 10 Users created (password: 123456)\n";
        echo "\nLogin credentials:\n";
        echo "Email: john.doe@example.com | Password: 123456\n";
        echo "Email: jane.smith@example.com | Password: 123456\n";
        echo "Email: mike.wilson@example.com | Password: 123456\n";
        echo "And 7 more users...\n";
    }
}
