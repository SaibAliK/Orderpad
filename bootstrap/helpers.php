<?php

use DB as DB;
use Auth as Auth;
use Mail as Mail;
use File as File;
use App\Models\Surgery;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

	function changeDate($date){
	    if(!empty($date)){
	        return Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
	    }else{
	        return '';
	    }
	}

	function uploadAvatar($file, $path){
	    $name = time().'-'.str_replace(' ', '-', $file->getClientOriginalName());
	    $file->move($path,$name);
	    return $path.'/'.$name;
	}


	function surgeries(){
    	return Surgery::all();
	}

	function pharmacies()
	{
	    return User::where('role', 'pharmacist')->get();
	}

	function branches()
	{
	    return User::where('role', 'pharmacist')->orWhere('role', 'pharmacist_staff')->get();
	}

	function sendMail($data)
	{
	    Mail::send($data['view'], ['data' => $data['data']], function ($message) use ($data) {
	        $message->to($data['to'], $data['name'])->subject($data['subject']);
	    });
    }


