<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveSpillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_spills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 11);
            $table->year('from_year', 11);
            $table->year('to_year', 11);
            $table->string('days', 11);
            $table->string('used', 11);
            $table->string('valid', 11);
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
        Schema::dropIfExists('leave_spills');
    }
}
