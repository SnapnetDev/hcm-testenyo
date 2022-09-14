<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Holiday extends Model
{
    protected $fillable = ['title', 'date','created_by','company_id'];
    protected $appends = array('o_date');
    public function user()
    {
        return $this->belongsTo('App\User','created_by');
    }
    // public function getDateAttribute($value)
    // {
    // 	return date('m/d/Y',strtotime($value));
    // }
    public function getODateAttribute()
    {
    	return date('m/d/Y',strtotime($this->date));
    }
    protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
    
}
