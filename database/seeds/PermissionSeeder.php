<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new=new \App\Permission();
        $new->permission_category_id='4';
        $new->name='Approve Shift Swap';
        $new->constant='approve_shift_swap';
        //$new->save();

        $new=new \App\Permission();
        $new->permission_category_id='4';
        $new->name='Approve Exemptions';
        $new->constant='approve_exemptions';
        $new->save();







    }
}
