<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldsZingle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zingle_integrations', function (Blueprint $table) {
            $table->string('appt_date_custom_field_id')->nullable();
            $table->string('appt_time_custom_field_id')->nullable();
            $table->string('first_name_custom_field_id')->nullable();
            $table->string('last_name_custom_field_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zingle_integrations', function (Blueprint $table) {
            $table->dropColumn('appt_date_custom_field_id');
            $table->dropColumn('appt_time_custom_field_id');
            $table->dropColumn('first_name_custom_field_id');
            $table->dropColumn('last_name_custom_field_id');
        });
    }
}
