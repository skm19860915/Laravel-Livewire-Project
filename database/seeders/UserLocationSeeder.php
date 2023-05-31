<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_location')->truncate();

        $location = Location::first()->id;
        $user = User::first()->id;

        DB::table('user_location')->insert([
            'user_id' => $user,
            'location_id' => $location,
            'primary'    => 1
        ]);
    }
}
