<?php

namespace App\Console\Commands;

use App\Company;
use App\Role;
use App\Traits\Biometric as BiometricTrait;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchEnyoUsersCommand extends Command
{
    use biometrictrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Enyo Users and match them to location';

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
        $this->fetchEnyoUsers();
    }

}

