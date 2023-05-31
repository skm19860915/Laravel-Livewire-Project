<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTreatmentTypeIdFromEmailJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_journeys', function (Blueprint $table) {
            $table->dropForeign('email_journeys_treatment_type_id_foreign');
            $table->dropColumn('treatment_type_id');
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
