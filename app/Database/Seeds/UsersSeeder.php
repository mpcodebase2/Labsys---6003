<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'username' => 'admin',
                'password' => '$2y$12$U5P6n8YY/4yUcfJxinssR.jpwDNKjOg7P1e91s9ymvFdBlE0..U/u', // Hashed password
                'nic' => '952444165v',
                'first_name' => 'Admin',
                'last_name' => 'Sha',
                'gender' => 'Male',
                'phone' => '0777678678',
                'email' => 'test@gmail.com',
                'specialization' => '',
                'dob' => '1995-03-05',
                'join_date' => '2024-03-04',
                'address' => 'Galle road, Colombo',
                'district' => 9,
                'active' => 1,
                'created_by' => 1,
                'last_login' => '2024-03-14 13:30:29',
                'created_at' => '2024-03-04 16:08:35',
                'updated_at' => '2024-03-14 13:30:29'
            ],
            // Add other data entries following the same format
        ];

        // Insert data
        $this->db->table('users')->insertBatch($data);
    }
}
