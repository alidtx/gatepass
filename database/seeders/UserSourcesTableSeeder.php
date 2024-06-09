<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = \Carbon\Carbon::now();
        \DB::table('user_sources')->insert([
            ['id' => 1,'name'=> 'CGL', 'created_by' => 1, 'created_at' => $time],
            ['id' => 2,'name'=> 'OTL', 'created_by' => 1, 'created_at' => $time],
            ['id' => 3,'name'=> 'SSL', 'created_by' => 1, 'created_at' => $time],
            ['id' => 4,'name'=> 'SSLCommerz', 'created_by' => 1, 'created_at' => $time],
            ['id' => 5,'name'=> 'EPZ', 'created_by' => 1, 'created_at' => $time],
        ]);
    }
}
