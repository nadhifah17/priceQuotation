<?php

namespace Database\Seeders;

use App\Models\CurrencyGroup;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CurrencyGroup::truncate();

        CurrencyGroup::insert([
            ['name' => 'IDR', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'USD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'JPY', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'THB', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
