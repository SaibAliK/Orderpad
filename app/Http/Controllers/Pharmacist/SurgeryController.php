<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surgery;

class SurgeryController extends Controller
{

    public function index()
    {
        $surgeries = Surgery::latest()->get();
        return view('pharmacist.surgery.surgery', get_defined_vars());
    }

    public function load_surgery()
    {
        return view('pharmacist.surgery.add_render');
    }

    public function create()
    {
        return view('pharmacist.surgery.add-edit');
    }
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
        ]);
        Surgery::create($request->all());
        return redirect()->route('pharmacist.surgery.index')
            ->with('success_msg', 'Surgery has been created.');
    }
    public function edit(Surgery $surgery)
    {
        return view('pharmacist.surgery.edit_render', get_defined_vars());
    }
    public function update(Request $request, Surgery $surgery)
    {
        request()->validate([
            'name' => 'required',
        ]);

        $surgery->update($request->all());
        return redirect()->route('pharmacist.surgery.index')
            ->with('success_msg', 'Surgery has been updated.');
    }
    public function remove($id)
    {
        $surgery = Surgery::find($id);
        $surgery->delete();
        return redirect()->back()->with('success_msg', 'List has been updated.');
    }
    public function destroy(Surgery $surgery)
    {
        $surgery->delete();
        return redirect()->back()->with('success_msg', 'List has been updated.');
    }
    public function show()
    {
    }
    public function collected()
    {
        $surgery = Surgery::whereHas('rxes', function ($q) {
            $q->where('status', 1);
        })->get();
        return view('pharmacist.surgery.rxes', get_defined_vars());
    }
    public function urgent_collection()
    {
        $surgery = Surgery::whereHas('rxes', function ($q) {
            $q->where('status', 1);
        })->get();
        return view('pharmacist.surgery.urgent_collection', get_defined_vars());
    }
    public function all_collection()
    {
        $surgery = Surgery::whereHas('rxes')->get();
        return view('pharmacist.surgery.all_collection', get_defined_vars());
    }
}
