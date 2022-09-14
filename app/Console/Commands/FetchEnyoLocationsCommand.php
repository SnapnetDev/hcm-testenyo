<?php

namespace App\Console\Commands;

use App\Company;
use App\Branch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchEnyoLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Enyo Locations';

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
   
    $branches= DB::connection('mysql2')->table('vw_location')->whereNotIn('Branch',['','--'])->select('Branch')->distinct()->get();
    foreach($branches as $branch){
        Branch::firstOrCreate(['name'=>$branch->Branch],
                [   'company_id'=>'5',
                    'email'=>$branch->Branch,
                    'address'=>$branch->Branch,
                    'manager_id'=>'2',
                    'region_id'=>'1',
                ]);
    }
    
    $company_locations_matchings= DB::connection('mysql2')->table('vw_company_location')->get();
    function mattch($company_locations_matchings,$locationId){
        $ddd= $company_locations_matchings->where('LocationId',$locationId)->first();
        if($ddd){
          return $ddd->CompanyLocationId;   
        }
        else{
            dd($locationId);
        }
    }
    
       $locations= DB::connection('mysql2')->table('vw_location')->whereNotIn('Branch',['','--'])->whereNotIn('LocationId',[108])->get();
       foreach ($locations as $location){
           $loc_branch=Branch::where('name',$location->Branch)->first();
           $loc_branch_id=$loc_branch->id;
           if($location->LocationId!="94" && $location->LocationId!="98"){
                Company::updateOrCreate(['idms_id'=>mattch($company_locations_matchings,$location->LocationId)],
                ['email'=>$location->Name,
                    'address'=>$location->Name,
                     'name'=>$location->Name,
                    'user_id'=>'1',
                    'branch_id'=>$loc_branch_id,
                    //'state_id'=>'2671',
                ]);
           }
          
                
          
           /*$com=new Company();
           $com->name=$location->Name;
           $com->email=$location->Name;
           $com->address=$location->Name;
           $com->user_id='1';
           $com->branch_id='1';
           $com->state_id='2671';
           $com->save();*/
          // $this->info($location->Name);
       }
    }
}
