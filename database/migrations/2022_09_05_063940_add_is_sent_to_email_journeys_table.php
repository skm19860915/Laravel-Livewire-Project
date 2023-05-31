<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSentToEmailJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_journeys', function (Blueprint $table) {
            $table->boolean('is_sent')->nullable()->after('treatment_type_id');
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
            $table->boolean('is_sent')->nullable()->after('treatment_type_id');
        });
    }
}
