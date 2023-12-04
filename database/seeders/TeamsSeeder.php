<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Team::insert([
                ['name' => 'ManCity'],
                ['name' => 'Chelsea'],
                ['name' => 'Everton'],
                ['name' => 'Leicester'],
            ]);
        });
    }
}
