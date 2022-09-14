<?php

namespace App\Console\Commands;


use App\AttendanceReport;
use App\FinancialReport;
use App\FinancialReportDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NewRunPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:payroll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payroll run';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    }
}
