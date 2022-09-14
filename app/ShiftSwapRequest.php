<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftSwapRequest extends Model
{
    protected $fillable=['owner_id','swapper_id','approved_by','status','user_daily_shift_id','reason','new_shift_id','date'];
    public function owner()
    {
        return $this->belongsTo('App\User','owner_id');
    }
    public function swapper()
    {
        return $this->belongsTo('App\User','swapper_id');
    }
    public function newShift()
    {
        return $this->belongsTo('App\Shift','new_shift_id');
    }
    public function approver()
    {
        return $this->belongsTo('App\User','approved_by');
    }
    public function userDailyShift()
    {
        return $this->belongsTo('App\UserDailyShift','user_daily_shift_id');
    }
}
