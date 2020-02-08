<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // add a Status column in the users table for Active,Suspected and Block User
            // 0 = active,
            // 1 = suspected,
            // 2 = block
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropping Status Column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
