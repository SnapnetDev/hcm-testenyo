<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_change_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('status');
            $table->string('reason');
            $table->text('details');
            $table->date('start_date');
            $table->string('created_by');
            $table->string('company_id');
            $table->string('approved')->default('pending');
            $table->string('approved_by');
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
        Schema::dropIfExists('status_change_requests');
    }
}
