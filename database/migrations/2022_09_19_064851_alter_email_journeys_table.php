<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmailJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_journeys', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('subject')->after('name');
            $table->text('body')->after('subject');
            $table->integer('trigger_date_type')->after('body');
            $table->integer('days')->after('trigger_date_type');
            $table->boolean('status')->nullable()->after('treatment_type_id');
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
