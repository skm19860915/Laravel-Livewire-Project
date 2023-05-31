<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
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
            DB::table('product_types')->insert([
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'ED'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'TRT'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'Lipoject'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'ESWT'],
                ['created_at' => now(), 'location_id' => $location->id, 'description' => 'Other'],
            ]);
        }

        DB::statement("SET foreign_key_checks=1");
    }
}
