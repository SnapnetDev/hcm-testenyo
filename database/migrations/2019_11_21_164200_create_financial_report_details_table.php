<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_report_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('role_id');
            $table->string('company_id');
            $table->string('finance_report_id');
            $table->string('days_worked')->default('0');
            $table->string('present')->default('0');
            $table->string('absent')->default('0');
            $table->string('late')->default('0');
            $table->string('amount_expected')->default('0');
            $table->string('amount_received')->default('0');
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
        Schema::dropIfExists('financial_report_details');
    }
}
