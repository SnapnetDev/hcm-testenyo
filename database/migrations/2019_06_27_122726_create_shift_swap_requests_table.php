<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftSwapRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_swap_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('owner_id');
            $table->string('swapper_id');
            $table->string('approved_by');
            $table->string('status');
            $table->string('user_daily_shift_id');
            $table->string('reason');
            $table->string('new_shift_id');
            $table->string('date');
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
        Schema::dropIfExists('shift_swap_requests');
    }
}
