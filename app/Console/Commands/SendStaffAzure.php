<?php

namespace App\Console\Commands;

use App\Traits\FaceMatchTrait;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SendStaffAzure extends Command
{
    use FaceMatchTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'picture:azure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pictures of staff to Azure';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setDefault();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $this->loadAzure();
        
    }
    private function loadAzure(){
        $users= User::select('id','image','image_id')->where('image','!=','')->where('image_id','=','')
        ->whereNotIn('id',[343,593,600,601,634,635,830,835,865,893,914,915,917,918,919,920,921,923,1048,1059])->get();
        $image_id='';
        foreach($users as $user){
            $url= asset('uploads/public/avatar'.$user->image);
            $urls=[$url];
            $image_id= $this->addFacetoList($urls);
            $image_id=$image_id[$url];
            User::where('id',$user->id)->update(['image_id'=>$image_id]);
        }
    }
    
    private function loadAzure2(){
        $users= User::all();
        //$users= User::whereIn('id',[29,30])->get();
        $image_id='';
        foreach($users as $user){
            if(File::exists('uploads/public/avatar/IDS/'.$user->emp_num.'.jpg')){
                $image='/IDS/'.$user->emp_num.'.jpg';
                $url= asset('uploads/public/avatar'.$image);
                $urls=[$url];
                
               // $image_id= $this->addFacetoList($urls);
               // $image_id=$image_id[$url];
            
            }
            elseif(File::exists('uploads/public/avatar/IDS/'.$user->emp_num.'.jpeg')){
                $image='/IDS/'.$user->emp_num.'.jpeg';
                $url= asset('uploads/public/avatar'.$image);
                $urls=[$url];
                //$image_id= $this->addFacetoList($urls);
                //$image_id=$image_id[$url];
            }
            else{
                $image='';
                $image_id='';
            }
            User::where('id',$user->id)->update(['image'=>$image,'image_id'=>$image_id]);
        }
    }
}
