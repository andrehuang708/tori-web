<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel();
        
        // Dummy users with password "123456"
        $users = [
            [
                'email' => 'john.doe@example.com',
                'password' => md5('123456' . 'salt1'),
                'phone_number' => '+62812345678',
                'salt' => 'salt1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'jane.smith@example.com',
                'password' => md5('123456' . 'salt2'),
                'phone_number' => '+62823456789',
                'salt' => 'salt2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'mike.wilson@example.com',
                'password' => md5('123456' . 'salt3'),
                'phone_number' => '+62834567890',
                'salt' => 'salt3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'sarah.johnson@example.com',
                'password' => md5('123456' . 'salt4'),
                'phone_number' => '+62845678901',
                'salt' => 'salt4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'david.brown@example.com',
                'password' => md5('123456' . 'salt5'),
                'phone_number' => '+62856789012',
                'salt' => 'salt5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'emily.davis@example.com',
                'password' => md5('123456' . 'salt6'),
                'phone_number' => '+62867890123',
                'salt' => 'salt6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'robert.miller@example.com',
                'password' => md5('123456' . 'salt7'),
                'phone_number' => '+62878901234',
                'salt' => 'salt7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'lisa.anderson@example.com',
                'password' => md5('123456' . 'salt8'),
                'phone_number' => '+62889012345',
                'salt' => 'salt8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'james.taylor@example.com',
                'password' => md5('123456' . 'salt9'),
                'phone_number' => '+62890123456',
                'salt' => 'salt9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'email' => 'mary.thomas@example.com',
                'password' => md5('123456' . 'salt10'),
                'phone_number' => '+62891234567',
                'salt' => 'salt10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($users as $user) {
            $userModel->insert($user);
        }

        echo "UserSeeder: Inserted " . count($users) . " users\n";
    }
}
