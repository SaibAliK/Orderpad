<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Surgery;
use Illuminate\Support\Facades\DB;
use App\Models\Rx;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Models\Patient;
use App\Models\PharmacyLogin;
use Carbon\Carbon;
use Session;
use Hash;

class AuthenticationController extends Controller
{
    protected $guard;
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }
    public function index(Request $req, $pharma = false)
    {
        $user =  Auth::user();
        if (Auth::check()) {
            $user =  Auth::user();
            if ($user->role === "admin" or $user->role === "admin_staff") {
                return redirect()->route('patient.index');
            }
            if ($user->role === "pharmacist_staff" or $user->role === "pharmacist" or $user->role === "driver") {
                return redirect()->route('pharmacist.patient.index');
            }
        } else {}
            if (!$pharma)
                return view('admin_login');
            else
                return view('login');
        }
        public function pharmacy(Request $req)
        {
            return $this->index($req, true);
        }
        public function branchLogin($id)
        {           
            $get_id = Auth::id();
            $this->guard->logout();
            session()->invalidate();
            session()->regenerateToken();
            auth('web')->loginUsingId($id);
            Session::put('admin_auth_id', $get_id);
            return redirect()->route('pharmacist.patient.index');
        }

        public function mainBranchLogin()
        {
            $get_id = Session::get('admin_auth_id');
            $this->guard->logout();
            session()->invalidate();
            session()->regenerateToken();
            auth('web')->loginUsingId($get_id);
            return redirect()->route('patient.index');
        }

        public function parentLogin(Request $req)
        {
            $req->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $user = User::whereEmail($req->email)->first();
            if ($user) {
                if (Hash::check($req->password, $user->password)) {
                    if ($user->role === 'pharmacist') { 
                    return $this->staffLogin($req);
                } else if ($user->role == 'pharmacist_staff') {
                    return $this->staffLogin($req);
                }
                 else if ($user->role == 'driver') {
                    return $this->staffLogin($req);
                }
                else
                    auth('web')->loginUsingId($user->id);
                Auth::guard('web')->attempt($req->only('email', 'password'));
                return $this->index($req);
            } else {
                return redirect()->back()->with('error_msg', 'Email and Password does not match!');
            }
        } else {
            return redirect()->back()->with('error_msg', 'User is does not exist!');
        }
    }

    public function staffLogin(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::whereEmail($req->email)->first();
        if ($user) {
            if (Hash::check($req->password, $user->password)) {
                if ($user->role === 'pharmacist_staff' or $user->role === 'pharmacist' or $user->role === 'driver') {
                    $this->guard->logout();
                    auth('web')->loginUsingId($user->id);
                    session(['staff_login_timestamp' => date('Y-m-d H:i:s')]);
                    return $this->index($req);
                }
            } else {
                return redirect()->back()->with('error_msg', 'Email and Password does not match!');
            }
        } else {
            return redirect()->back()->with('error_msg', 'User is not exist!');
        }
        if (Hash::check($req->password, $user->password)) {
            if ($user->role === 'pharmacist_staff') {
                $this->guard->logout();
                auth('web')->loginUsingId($user->id);
                session(['staff_login_timestamp' => date('Y-m-d H:i:s')]);
                return $this->index($req);
            }
        }
        return back()->with('error', 'Invalid Details!');
    }
    public function staffLogout()
    {
        Auth::logout();
        return redirect()->route('site.login');
    }
    public function pharmacyLogout(Request $req)
    {
        PharmacyLogin::where('ip', $req->ip())->delete();
        return redirect()->route('site.login');
    }
    public function staffSignup(Request $req)
    {
        $check = PharmacyLogin::where('ip', $req->ip())->first();
        if (!$check) {
            return view('staff_signup');
        } else {
            return redirect()->back();
        }
    }
    public function staffStore(Request $req)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
        ]);

        $user = User::Create([
            'name' => $req->name,
            'email' => $req->email,
            'role' => 'pharmacist_staff',
            //'parent_id' => PharmacyLogin::where('ip', $req->ip())->first()->pharmacy_id,
            'password' => Hash::make($req->password),
            'length' => strlen($request->password)    
        ]);
        if($user)
        {
            return redirect()->route('site.login')->with('success_msg', 'Staff account is created successfully.');
        }
    }
    public function profile()
    {
        $user = Auth::user();
        if ($user->role == "admin") {
            return view('profile.show', get_defined_vars());
        } else {
            return view('profile.pharmacy_profile_show', get_defined_vars());
        }
    }
    public function profileUpdate(Request $request)
    {
        $user = User::find($request->user_id);
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->email) {
            $request->validate([
                'email' => 'required|Email',
            ]);
            $user->email = $request->email;
        }
        $user->update();
        return redirect()->route('profile')->with('success_msg', 'User profile Update successfully');
    }
    public function signout()
    {
        $user = Auth::user();
        if ($user->role == "admin") {
            Auth::logout();
            return redirect()->route('site.login');
        } else {
            Auth::logout();
            return redirect('pharmacy/login');
        }
    }
    public function resetPassword(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $password = $user->password;
            $this->validate($request, [
                'oldpassword'      => 'required',
                'newpassword'      => 'required|min:8',
                'confirm_password' => 'required|same:newpassword'
            ]);
            if (Hash::check($request->oldpassword, $password)) {
                $user->password = Hash::make($request->newpassword);
                $user->length = strlen($request->newpassword);
                $user->save();
                return redirect('/login')->with('success_msg', 'Password changed successfully');
            } else {
                return redirect()->back()->with('error_msg', 'Incorrect Old Password');
            }
        } else {
            return redirect()->back()->with('error_msg', 'User is does not exist');
        }
    }
}
