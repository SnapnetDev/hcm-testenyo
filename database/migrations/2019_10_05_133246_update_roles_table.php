<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('amount')->default('1');
            $table->string('days')->default('1');
            $table->string('off')->default('1');
            $table->string('daily_pay')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('days');
            $table->dropColumn('off');
            $table->dropColumn('daily_pay');
        });
    }
}
