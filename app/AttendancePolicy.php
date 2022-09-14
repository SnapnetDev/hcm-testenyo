<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendancePolicy extends Model
{
    protected $fillable=['workflow_id'];


    public function workflow()
    {
        return $this->belongsTo('App\Workflow','workflow_id');
    }
}
