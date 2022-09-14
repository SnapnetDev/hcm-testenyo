<?php

use Illuminate\Database\Seeder;

class AttendanceSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new=new \App\Setting();
        $new->name='before_shift_time';
        $new->company_id='0';
        $new->value='1';
        $new->save();


    }
}
