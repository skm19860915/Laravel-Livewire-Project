<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('username')->after('name');
          $table->tinyInteger('banned')->default(0);
          $table->text('ban_reason')->nullable();
          $table->string('last_ip')->nullable();
          $table->tinyInteger('role');
          $table->string('first_name');
          $table->string('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('username');
          $table->dropColumn('banned');
          $table->dropColumn('ban_reason');
          $table->dropColumn('last_ip');
          $table->dropColumn('role');
          $table->dropColumn('first_name');
          $table->dropColumn('last_name');
        });
    }
}
