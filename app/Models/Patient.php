<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Surgery;
use Illuminate\Database\Eloquent\SoftDeletes;
class Patient extends Model
{
	protected $guarded = ['drug', 'quantity', 'order_status', 'checked_hidden'];
    use HasFactory, SoftDeletes;
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'pharmacy_id');
    }
    public function surgery()
    {
        return $this->belongsTo('App\Models\Surgery');
    }
    public function rx()
    {
        return $this->belongsTo('App\Models\Rx','rx_id','id');
    }
    public function orderedMeds()
    {
        $rx = Rx::withTrashed()->find($this->rx_id);
        if($rx)
        {
           $meds = $rx->medications->where('order_status', 1)->pluck('drug_name')->count();
           // return implode("<br />", $meds->toArray());
           return $meds;
       }
       return '';
   }
   public function allMeds()
   {
    $rx = Rx::withTrashed()->find($this->rx_id);
    if($rx)
    {
       $meds = $rx->medications->pluck('drug_name')->count();
           // return implode("<br />", $meds->toArray());
       return $meds;
   }
   return '';
}
public function medications()
{
    if($this->rx)
    {
       $meds = $this->rx->medications->where('order_status', 1)->pluck('drug_name')->count();
           // return implode("<br />", $meds->toArray());
       return $meds;
   }
   return '';
}
}
