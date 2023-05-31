<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->comment('sales_counselor');
            $table->double('total')->nullable();
            $table->double('amount_paid_during_office_visit')->nullable();
            $table->double('balanc_during_visit')->nullable();
            $table->double('payment_increments')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
