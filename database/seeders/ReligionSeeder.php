<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an array of records to insert
        $religions = [
            ['name' => 'Islam'],
            ['name' => 'Buddhism'],
            ['name' => 'Christianity'],
            ['name' => 'Hinduism'],
            ['name' => 'Taoism'],
        ];

        // Bulk insert the records
        Religion::insert($religions);
    }
}
