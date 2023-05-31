<?php

namespace Database\Seeders;

use App\Models\ScheduleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScheduleType::truncate();

        DB::table('schedule_types')->insert([
            ['description' => 'Unconfirmed / No Show'],
            ['description' => 'Confirmed/Ticket'],
            ['description' => 'Confirmed No Show'],
            ['description' => 'Cancelled'],
            ['description' => 'Rescheduled'],
            ['description' => 'Voicemail'],
            ['description' => 'No Sale / Rescheduled during office visit / Marked for Revisit'],
            ['description' => 'Calendar Block'],
        ]);
    }
}
