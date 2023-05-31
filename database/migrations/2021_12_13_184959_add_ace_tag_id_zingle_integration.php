<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAceTagIdZingleIntegration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zingle_integrations', function (Blueprint $table) {
            $table->string('ace_tag_id')->nullable();
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
            $table->dropColumn('ace_tag_id');
        });
    }
}
