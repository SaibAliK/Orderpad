<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Models\SurgeryNotes;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    public function index()
    {
        $surgeries = Surgery::latest()->get();
        return view('admin.surgery.surgery', get_defined_vars());
    }
    public function load_surgery()
    {
        return view('admin.surgery.add_render');
    }
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'address' => 'required',
        ]);
        $sur = Surgery::where('email', $request->email)->first();
        if ($sur) {
            return redirect()->back()->with('error_msg', 'Email Already Exist');
        }
        $surgery = new Surgery();
        $surgery->name = $request->name;
        $surgery->email = $request->email;
        $surgery->address = $request->address;
        $surgery->phone = $request->phone;
        $surgery->fax = $request->fax;
        $surgery->ordering_method = $request->ordering_method;
        $surgery->other_ordering_method = $request->other_ordering_method ?? "";
        $surgery->save();
        // save notes
        if ($request->notes != "" && !is_null($request->notes[0])) {
            for ($i = 0; $i < count($request->notes); $i++) {
                $notes = new SurgeryNotes();
                $notes->surgery_id = $surgery->id;
                $notes->notes = $request->notes[$i];
                $notes->save();
            }
        }
        return redirect()->back()->with('success_msg', 'Surgery has been created.');
    }
    public function edit(Surgery $surgery)
    {
        return view('admin.surgery.edit_render', get_defined_vars());
    }

    public function update(Request $request, Surgery $surgery)
    {
        request()->validate([
            'name' => 'required',
            'address' => 'required',
        ]);
        $surgery->name = $request->name;
        $surgery->email = $request->email;
        $surgery->address = $request->address;
        $surgery->phone = $request->phone;
        $surgery->fax = $request->fax;
        $surgery->ordering_method = $request->ordering_method ?? "";
        $surgery->other_ordering_method = $request->other_ordering_method ?? "";
        $surgery->save();
        // save notes
        if ($request->notes != "" && !is_null($request->notes[0])) {
            $surgery->notes()->delete();
            for ($i = 0; $i < count($request->notes); $i++) {
                $notes = new SurgeryNotes();
                $notes->surgery_id = $surgery->id;
                $notes->notes = $request->notes[$i];
                $notes->save();
            }
        }
        return redirect()->back()->with('success_msg', 'Surgery has been updated.');
    }
    public function destroy($id)
    {
        $surgery = Surgery::find($id);
        $surgery->delete();
        return redirect()->back()->with('success_msg', 'Surgery has been deleted.');
    }
    //
    public function show(Surgery $surgery)
    {
    }
    public function viewSurgeryRx($id)
    {
        $surgery = Surgery::where('id', $id)->first();
        return view('admin.surgery.surgery_rx', get_defined_vars());
    }
    public function urgent_collection()
    {
        $surgery = Surgery::whereHas('rxes', function ($q) {
            $q->where('status', 1);
        })->get();
        return view('admin.surgery.urgent_collection', get_defined_vars());
    }
    public function all_collection()
    {
        $surgery = Surgery::whereHas('rxes')->get();
        return view('admin.surgery.all_collection', get_defined_vars());
    }
}
