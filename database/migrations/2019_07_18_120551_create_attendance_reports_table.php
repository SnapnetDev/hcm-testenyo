<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('attendance_id')->nullable();
            $table->date('date')->nullable();
            $table->string('first_clockin')->nullable()->default('0');
            $table->string('last_clockout')->nullable();
            $table->string('status')->nullable();
            $table->string('hours_worked')->nullable();
            $table->string('overtime')->nullable();
            $table->string('shift_start')->nullable();
            $table->string('shift_end')->nullable();
            $table->string('shift_name')->nullable();
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
        Schema::dropIfExists('attendance_reports');
    }
}
