<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rx;
class Medication extends Model
{
	protected $guarded = [];
    use HasFactory;

    public function post()
    {
        return $this->belongsTo('App\Models\Rx');
    }

}
