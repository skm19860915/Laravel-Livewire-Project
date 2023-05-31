<?php

use App\Models\Location;
use App\Models\ScheduleType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Location::class)->constrained();
            $table->foreignIdFor(ScheduleType::class)->constrained();
            $table->date('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_blocks');
    }
}
