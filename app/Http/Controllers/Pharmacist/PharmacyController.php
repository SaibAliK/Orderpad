<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PharmacyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PharmacyController extends Controller
{
    public function index()
    {
        $pharmacists = User::where('role', 'pharmacist')->get();
        return view('pharmacist.pharmacy.pharmacy', get_defined_vars());
    }

    public function create()
    {
        return view('pharmacist.pharmacy.add-edit');
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            $random_password = Str::random(10);
            $user = User::Create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'pharmacist',
                'password' => Hash::make($random_password)
            ]);
            $pharmacyProfile  = new PharmacyProfile();
            $pharmacyProfile->user_id = $user->id;
            $pharmacyProfile->address = $request->address;
            $pharmacyProfile->save();
            sendMail([
                'view' => 'email.pharmacy',
                'to' => $user->email,
                'subject' => 'Pharmacy Account has been created.',
                'name' => $user->name,
                'data' => [
                    'user' => $user->name,
                    'email' => $user->email,
                    'password' => $random_password,
                ]
            ]);
        }
        return redirect()->route('pharmacist.pharmacy.index')->with('success_msg', 'New Pharmacy has been created.');
    }
    public function edit($id)
    {
        $pharmacy = User::where("id", $id)->first();
        if (empty($pharmacy)) {
            return redirect()->back()->with('error_msg', 'The Pharmacy is not exist');
        }
        return view('admin.pharmacy.add-edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $pharmacy = User::where("id", $id)->first();
        if (empty($pharmacy)) {
            return redirect()->back()->with('error_msg', 'User is not exist');
        }
        $pharmacy->name = $request->name;
        $pharmacy->email = $request->email;
        $pharmacy->role = 'pharmacist';
        $pharmacy->save();
        $pharmacyProfile = PharmacyProfile::where("user_id", $id)->first();
        if (empty($pharmacyProfile)) {
            return redirect()->back()->with('error_msg', 'Pharmacy is not exist');
        }
        $pharmacyProfile->address = $request->address;
        $pharmacyProfile->save();
        return redirect()->route('pharmacist.pharmacy.index')->with('success_msg', 'Pharmacy has been updated.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return redirect()->back()->with('error_msg', 'Pharmacy is not exist');
        }
        $user->delete();
        return redirect()->route('pharmacist.pharmacy.index')->with('success_msg', 'Pharmacy has been deleted.');
    }
}
