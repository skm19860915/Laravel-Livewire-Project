<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\PatientSeeder;
use Database\Seeders\ScheduleTypeSeeder;
use Database\Seeders\UserLocationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");

        $this->call([
            RoleSeeder::class,
            ScheduleTypeSeeder::class,
            UserSeeder::class,
            LocationSeeder::class,
            UserLocationSeeder::class,
            PatientSeeder::class
        ]);

        DB::statement("SET foreign_key_checks=1");
    }
}
