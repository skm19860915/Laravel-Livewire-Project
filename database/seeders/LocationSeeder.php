<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::truncate();
        $now = Carbon::now();
        DB::table('locations')->insert([
            [
                'location_name' => 'Test Clinic',
                'address'       => '123 Main Street',
                'city'          => 'Franklin',
                'state'         => 'TN',
                'zip'           => '37064',
                'time_zone'     => 'CST',
                'created_at'    => $now,
                'updated_at'    => $now
            ]
        ]);
    }
}
