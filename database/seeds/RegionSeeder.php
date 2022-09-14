<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new=new \App\Region();
        $new->name='South West';
        $new->save();

        $new=new \App\Region();
        $new->name='North East';
        $new->save();

        $new=new \App\Region();
        $new->name='North West';
        $new->save();

        $new=new \App\Region();
        $new->name='South South';
        $new->save();

    }
}
