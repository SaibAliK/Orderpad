<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyLogin extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pharmacy()
    {
       return $this->belongsTo('App\Models\User');
    }
}

