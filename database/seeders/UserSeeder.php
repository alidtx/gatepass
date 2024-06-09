<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            [
                'name' => 'Md. Abdul Kader Zilani', 
                'email' => 'towsifzilani@gmail.com',
                'password' => bcrypt('password'),
                'user_type' => 'Admin',
                'department_id' => 3,
                'user_source_id' => 1,
                'has_gatepass_approval_permission' => 1,
                'role_id' => 2
            ],
            [
                'name' => 'Raiyad Raad', 
                'email' => 'raad@gmail.com',
                'password' => bcrypt('password'),
                'user_type' => 'Head of MMD',
                'department_id' => 4,
                'user_source_id' => 3,
                'has_gatepass_approval_permission' => 0,
                'role_id' => 3
            ],
            [
                'name' => 'John Doe', 
                'email' => 'john.doe@gmail.com',
                'password' => bcrypt('password'),
                'user_type' => 'IT',
                'department_id' => 5,
                'user_source_id' => 4,
                'has_gatepass_approval_permission' => 0,
                'role_id' => 4
            ],
        ]);
    }
}
