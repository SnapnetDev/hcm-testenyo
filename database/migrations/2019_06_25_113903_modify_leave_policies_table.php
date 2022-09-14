<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLeavePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_policies', function (Blueprint $table) {
          
             $table->string('uses_spillover', 11)->default(0);
             $table->string('uses_maximum_spillover', 11)->default(0)->after('uses_spillover');
             $table->string('spillover_length', 11)->default(0)->after('uses_maximum_spillover');
             $table->string('spillover_month', 11)->default(12)->after('spillover_length');
             $table->string('spillover_day', 11)->default(31)->after('spillover_month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
