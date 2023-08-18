<?php

namespace Database\Seeders;

use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Material::truncate();
        Material::insert([
            ['name' => 'Resin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Steel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Urethane', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Fabric', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
