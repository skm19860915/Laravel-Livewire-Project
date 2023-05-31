<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTreatmentTypeIdToEmailJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_journeys', function (Blueprint $table) {
            $table->foreignId('treatment_type_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_journeys', function (Blueprint $table) {
            $table->foreignId('treatment_type_id')->nullable()->constrained();
        });
    }
}
