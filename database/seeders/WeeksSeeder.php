<?php

namespace Database\Seeders;

use App\Models\Week;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeeksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Week::insert([
                ['title' => '1 st'],
                ['title' => '2 nd'],
                ['title' => '3 rd'],
                ['title' => '4 th'],
                ['title' => '5 th'],
                ['title' => '6 th'],
            ]);
        });
    }
}
