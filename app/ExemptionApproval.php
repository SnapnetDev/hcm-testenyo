<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExemptionApproval extends Model
{
    protected $fillable=['exemption_id','stage_id','approver_id','comments','status'];

    public function exemption()
    {
        return $this->belongsTo('App\Exemption','exemption_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User','approver_id');
    }
    public function stage()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }
}
