<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AttendanceTableSeeder::class);
        $this->call(AttendanceSettingsSeeder::class);
        $this->call(RegionSeeder::class);
    }
}
