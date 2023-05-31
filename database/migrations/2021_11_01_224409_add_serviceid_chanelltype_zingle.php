<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceidChanelltypeZingle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zingle_integrations', function (Blueprint $table) {
            $table->string('service_id')->nullable();
            $table->string('channel_type_id')->nullable();
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
            $table->dropColumn('service_id');
            $table->dropColumn('channel_type_id');
        });
    }
}
