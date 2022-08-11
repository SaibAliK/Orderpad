<?php

namespace App\Http\Controllers;

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
        return view('admin.pharmacy.pharmacy', get_defined_vars());
    }

    public function load_pharmacy()
    {
        return view('admin.pharmacy.add_render');
    }
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'address' => 'required',
        ]);
        $user = User::where("email", $request->email)->first();

        if (!$user) {
            $random_password = Str::random(8);

            $user = User::Create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'pharmacist',
                'password' => Hash::make($random_password),
                'length' => strlen($random_password)
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

        return redirect()->route('pharmacy.index')->with('success_msg', 'New Pharmacy has been created.');
    }

    public function edit($id)
    {
        $pharmacy = User::where("id", $id)->first();
        return view('admin.pharmacy.edit_render', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'address' => 'required',
        ]);

        $pharmacy = User::where("id", $id)->first();
        if($pharmacy)
        {
            $pharmacy->name = $request->name;
            $pharmacy->email = $request->email;
            $pharmacy->role = 'pharmacist';
            $pharmacy->save();
        }
        $pharmacyProfile = PharmacyProfile::where("user_id", $id)->first();
        if($pharmacyProfile)
        {
            $pharmacyProfile->address = $request->address;
            $pharmacyProfile->save();
        }
        else
        {
            return redirect()->route('pharmacy.index')->with('error_msg', 'Pharmacy profile not found.');
        }

        return redirect()->route('pharmacy.index')->with('success_msg', 'Pharmacy has been updated.');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('pharmacy.index')->with('success_msg', 'Pharmacy has been deleted.');
    }
}
