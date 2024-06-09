<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = \Carbon\Carbon::now();
        \DB::table('departments')->insert([
            ['id' => 1,'name'=> 'IT', 'created_by' => 1, 'created_at' => $time],
            ['id' => 2,'name'=> 'MMD', 'created_by' => 1, 'created_at' => $time],
            ['id' => 3,'name'=> 'Commercial VAT & Local Acceptance', 'created_by' => 1, 'created_at' => $time],
            ['id' => 4,'name'=> 'Commercial & Shipping', 'created_by' => 1, 'created_at' => $time],
            ['id' => 5,'name'=> 'Marketing & Shipping-Admim', 'created_by' => 1, 'created_at' => $time],
            ['id' => 6,'name'=> 'Commercial Export', 'created_by' => 1, 'created_at' => $time],
            ['id' => 7,'name'=> 'C&F Dhaka Airport & Customs', 'created_by' => 1, 'created_at' => $time],
            ['id' => 8,'name'=> 'Shipping', 'created_by' => 1, 'created_at' => $time],
            ['id' => 9,'name'=> 'C&F CTG Airport', 'created_by' => 1, 'created_at' => $time],
            ['id' => 10,'name'=> 'F&A', 'created_by' => 1, 'created_at' => $time],
            ['id' => 11,'name'=> 'Industrial Engineering', 'created_by' => 1, 'created_at' => $time],
            ['id' => 12,'name'=> 'PRD (Cutting)', 'created_by' => 1, 'created_at' => $time],
            ['id' => 13,'name'=> 'PRD (Sewing to Shipping)', 'created_by' => 1, 'created_at' => $time],
            ['id' => 14,'name'=> 'Commercial Banking', 'created_by' => 1, 'created_at' => $time]
        ]);
    }
}
