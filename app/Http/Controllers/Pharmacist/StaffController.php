<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('pharmacist_staff');
    }
    public function index()
    {
        $staff = User::where('role', 'pharmacist_staff')->get();
        return view('pharmacist.staff.staff', get_defined_vars());
    }
    public function create()
    {
        return view('pharmacist.staff.add-edit');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
        ]);
        $user = User::where("email", $request->email)->first();

        if (!$user) {
            $random_password = Str::random(10);
            $user = User::Create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'pharmacist_staff',
                'password' => Hash::make($random_password),
                'parent_id' => Auth::id()
            ]);
            // send email credntials
            sendMail([
                'view' => 'email.pharmacist_staff',
                'to' => $user->email,
                'subject' => 'OrderPad Created Staff Account',
                'name' => $user->name,
                'data' => [
                    'user' => $user->name,
                    'email' => $user->email,
                    'pharmacy_name' => Auth::user()->name,
                    'password' => $random_password,
                ]
            ]);
        }
        return redirect()->route('pharmacist.staff.index')->with('success_msg', 'Staff member has been created.');
    }

    public function edit($id)
    {
        $staff = User::where("id", $id)->first();
        if (empty($staff)) {
            return redirect()->back()->with('error_msg', 'staff is not exist');
        }
        return view('pharmacist.staff.add-edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . "$id" . '|email',
        ]);

        $staff = User::where("id", $id)->first();
        if (empty($staff)) {
            return redirect()->back()->with('error_msg', 'staff is not exist');
        }
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = 'pharmacist_staff';
        $staff->save();
        return redirect()->route('pharmacist.staff.index')->with('success_msg', 'Staff Member data has been updated.');
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return redirect()->back()->with('error_msg', 'staff is not exist');
        }
        $user->delete();
        return redirect()->route('pharmacist.staff.index')->with('success_msg', 'Member has been deleted.');
    }
}
