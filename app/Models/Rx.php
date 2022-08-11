<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Medication;
use App\Models\User;
use App\Models\Surgery;
use Illuminate\Database\Eloquent\SoftDeletes;
class Rx extends Model
{
	protected $guarded = [];
    use HasFactory, SoftDeletes;
     public function medications()
    {
        return $this->hasMany('App\Models\Medication');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'pharmacy_id');
    }
    public function surgery()
    {
        return $this->belongsTo('App\Models\Surgery');
    }
    public function patients()
    {
        return $this->hasOne('App\Models\Patient','rx_id','id');
    }
}