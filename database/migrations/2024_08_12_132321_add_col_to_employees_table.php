<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('country');
            $table->date('joining_date');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('designation_id')->constrained('designations');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('office_shift_id')->constrained('office_shifts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('gender');
            $table->dropColumn('dob');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('country');
            $table->dropColumn('joining_date');
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
            $table->dropForeign(['designation_id']);
            $table->dropColumn('designation_id');
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->dropForeign(['office_shift_id']);
            $table->dropColumn('office_shift_id');            
        });
    }
}
