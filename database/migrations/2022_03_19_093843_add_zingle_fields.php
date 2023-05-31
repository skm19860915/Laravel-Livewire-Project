<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZingleFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zingle_integrations', function (Blueprint $table) {
            $table->string('sign_up_date_custom_field_id')->nullable();
            $table->string('ed_treatment_custom_field_id')->nullable();
            $table->string('trt_treatment_custom_field_id')->nullable();
            $table->string('eswt_treatment_custom_field_id')->nullable();
            $table->string('treatment_end_date_custom_field_id')->nullable();
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
            $table->string('sign_up_date_custom_field_id')->nullable();
            $table->string('ed_treatment_custom_field_id')->nullable();
            $table->string('trt_treatment_custom_field_id')->nullable();
            $table->string('eswt_treatment_custom_field_id')->nullable();
            $table->string('treatment_end_date_custom_field_id')->nullable();
        });
    }
}
