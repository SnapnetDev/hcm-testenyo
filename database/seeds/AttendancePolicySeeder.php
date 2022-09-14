<?php

use Illuminate\Database\Seeder;

class AttendancePolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new=new \App\AttendancePolicy();
        $new->workflow_id='1';
        $new->save();
    }
}
