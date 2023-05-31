<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPatientIdFromEmailJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_journeys', function (Blueprint $table) {
            $table->dropForeign('email_journeys_patient_id_foreign');
            $table->dropColumn('patient_id');
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
            //
        });
    }
}
