<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rx;
use App\Models\SurgeryNotes;

class Surgery extends Model
{
	protected $guarded = [];
    use HasFactory;


    public function rxes()
    {
        return $this->hasMany('App\Models\Rx');
    }
    public function notes()
    {
        return $this->hasMany('App\Models\SurgeryNotes');
    }

}
