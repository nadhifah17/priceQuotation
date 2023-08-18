<?php

namespace Database\Seeders;

use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Process::truncate();

        Process::insert([
            ['name' => 'Cutting Fabric', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sewing Fabric', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sewing Leather', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Urethane', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Injection', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Progressive', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tandem (Few)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tandem (Mass)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Fine Blanking', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Cutting Pipe', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bending Pipe', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Chamfering Pipe', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Notching Pipe', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'CO2 Welding', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Assy Process', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
