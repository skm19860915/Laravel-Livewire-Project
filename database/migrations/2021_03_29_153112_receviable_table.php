<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReceviableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receivables', function (Blueprint $table) {
            //
            $table->foreignId('ticket_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('due')->nullable();
            $table->double('payment_owed')->nullable();
            $table->double('balance')->nullable();
            $table->boolean('proccess')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receivables', function (Blueprint $table) {
            //
        });
    }
}
