<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Resources\Json\JsonResource;
 
class CompanyResource extends JsonResource
{
    protected $fillable = ['name', 'email','address','user_id','logo','branch_id','state_id','biometric_serial','idms_id','owner','status','pay_full_days'];
    protected $appends=['last_seen','last_shift'];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'user_id' => $this->user_id,
            'logo' => $this->logo,
            'branch_id' => $this->branch_id,
            'state_id' => $this->state_id,
            'biometric_serial' => $this->biometric_serial,
            'idms_id' => $this->idms_id,            
            'owner' => $this->owner,
            'status' => $this->status,
            'pay_full_days' => $this->pay_full_days,
            'last_seen' => $this->last_seen,
            'last_shift' => $this->last_shift,
        ];
    }
}

