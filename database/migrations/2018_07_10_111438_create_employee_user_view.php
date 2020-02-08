<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeUserView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creating view
        DB::statement('CREATE VIEW EmployeeUser AS (SELECT emp.*, usr.status FROM employees emp inner join users usr on usr.context_id = emp.id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement('DROP VIEW IF EXISTS EmployeeUser');
    }
}
