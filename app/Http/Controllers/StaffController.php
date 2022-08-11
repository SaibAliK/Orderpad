<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin_staff');
    }
    public function index()
    {
        $staff = User::all();
        return view('admin.staff.list', get_defined_vars());
    }
    public function create()
    {
        return view('admin.staff.add-edit' , get_defined_vars());
    }
    public function store(Request $request)
    {
        request()->validate([
            'email' => 'required|unique:users,email|email',
            'name' => 'required',
            'role' => 'required',
            'password'  => 'required|min:8',
            'confirm_password' =>'required|same:password'
        ]);
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            //$random_password = Str::random(10);
            $user = User::Create([
                'name' => $request->name,
                'email' => $request->email,
                'role' =>  $request->role,
                'password' => Hash::make($request->password),
                'parent_id' => Auth::id(),
                'length' => strlen($request->password)
            ]);
            // send email credntials
            sendMail([
                'view' => 'email.admin_staff',
                'to' => $user->email,
                'subject' => 'OrderPad Created Staff Account',
                'name' => $user->name,
                'data' => [
                    'user' => $user->name,
                    'email' => $user->email,
                    'password' => $request->password,
                ]
            ]);
        }
        return redirect()->route('staff.index')->with('success_msg', 'Staff member has been created.');
    }
    public function edit($id)
    {
        $staff = User::where("id", $id)->first();
        $len = $staff->length;
        $str_leng[] = "";
        for ($i=0; $i < $len ; $i++) { 
            $str_leng[$i] = ".";
        }
        $make_string = implode("",$str_leng);
        return view('admin.staff.add-edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'email' => 'required|email',
            'name' => 'required',
            'role' => 'required',
            'password'  => 'required|min:8',
            'confirm_password' =>'required|same:password'
        ]);

        $staff = User::where("id", $id)->first();
        $staff->name = $request->name;
        $staff->role =  $request->role;
        if($request->email !== $staff->email)
        {
            $staff->email = $request->email;
        }
        if($request->password)
        {
            $staff->password = Hash::make($request->password);
            $staff->length = strlen($request->password);
        }
        $staff->save();

        return redirect()->route('staff.index')->with('success_msg', 'Staff Member data has been updated.');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('staff.index')->with('success_msg', 'Member has been deleted.');
    }
    public function show($id)
    {
        User::find($id)->delete();
        return redirect()->route('staff.index')->with('success_msg', 'Member has been deleted.');
    }
}
