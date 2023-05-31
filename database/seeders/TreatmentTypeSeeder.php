<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");

        ProductType::truncate();

        $locations = Location::all();
        foreach ($locations as $location) {
            DB::table('treatment_types')->insert([
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'ED Treatment'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'TRT Treatment'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'Both'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'None'],
            ]);
        }

        DB::statement("SET foreign_key_checks=1");
    }
}
