<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToZingleIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zingle_integrations', function (Blueprint $table) {
            $table->json('contact_custom_fields')->after('channel_type_id')->nullable();
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
            $table->dropColumn('contact_custom_fields');
        });
    }
}
