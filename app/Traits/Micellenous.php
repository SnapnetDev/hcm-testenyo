<?php
namespace App\Traits;

 
/**
 *
 */
trait Micellenous
{
 
 public function fiscalYear(){


 }

 public function pilotGoals(){

   $goals = \App\Goal::whereHas('goalcat',function($query){
   						$query->where('category','Pilot');
   					})->get();
      
    return $goals;
}


 public function goals($column,$emp_id,$quarter,$date){

   $goals = \App\Goal::where('goal_cat_id',$column) 
   					->where(['assigned_to'=>$emp_id,'quarter'=>$quarter])
   					->whereYear('created_at',session('FY'))
   					->get();
   			 
    return $goals;
}



}
