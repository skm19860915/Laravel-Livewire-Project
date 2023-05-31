<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->double('amount')->nullable();
            $table->double('remaining_balance')->nullable();
            $table->foreignId('ticket_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('on_visit')->default(0)->nullable();
            $table->boolean('refund')->default(0)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
