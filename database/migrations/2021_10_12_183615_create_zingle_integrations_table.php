<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZingleIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zingle_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->string('zingle_username');
            $table->string('zingle_password');
            $table->unsignedTinyInteger('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zingle_integrations');
    }
}
