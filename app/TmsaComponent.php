<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class TmsaComponent extends Model
{
    protected $table='tmsa_components';
    protected $fillable=['company_id','name','constant','amount','status','type','taxable'];

    public function company()
    {
    	return $this->belongsTo('App\Company','company_id');
    }
     protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}
