<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Helpers\Helper\changeDate;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Rx;
use App\Models\Medication;
use Auth;
use Carbon\Carbon;


class PatientController extends Controller
{
    public function index(Request $req)
    {
        $tomorrow_date = Carbon::now()->addDay(1);
        $patients = Patient::where('pharmacy_id', Auth::user()->parent_id)->whereDate('order_date', '>=', $tomorrow_date)->get();
        $pharmacy = User::find(Auth::id());
        //$rxes = Rx::doesntHave('patients')->withTrashed()->get();
        $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->withTrashed()->get();
        //$rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->whereDate('order_date', '>=', $tomorrow_date)->doesntHave('patients')->withTrashed()->get();
        return view('pharmacist.patient.patient', get_defined_vars());
    }
    public function order()
    {
        $date = date('Y-m-d');
        $orders = Patient::whereDate('next_order_date', '<=', $date)
            ->where('pharmacy_id', Auth::user()->parent_id)
            ->orWhereDate('order_date', '=', $date)
            ->orWhereDate('previous_order_date', '=', $date)
            ->orWhere([['order_date', '<=', $date], ['previous_order_date', '=', 'NULL']])
            ->get();
        //$rxes = Rx::withTrashed()->get();
        //$rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->doesntHave('patients')->withTrashed()->get();
        $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->withTrashed()->get();
        return view('pharmacist.patient.order', get_defined_vars());
    }
    public function pendingOrder()
    {
        $date = date('Y-m-d');
        $orders = Patient::where('pendingOrder', 'true')->get();
        return view('pharmacist.patient.pending_order', get_defined_vars());
    }
    public function completedOrder()
    {
        $orders = Patient::whereHas('rx', function ($q) {
            $q->whereHas('medications', function ($sq) {
                $sq->where('status', "1");
            });
        })->get();
        return view('pharmacist.patient.completed_order', get_defined_vars());
    }
    public function create()
    {
        return view('pharmacist.patient.add');
    }

    public function load_patient($id)
    {
        $rx = Rx::withTrashed()->find($id);
        if ($rx) {
            return view('pharmacist.patient.add_render', get_defined_vars());
        } else {
            return response()->json(['error' => '1'], 200);
        }
    }

    public function store(Request $request)
    {
        request()->validate([
            'weeks' => 'required',
            'order_date' => 'required',
            'medication_date' => 'required',
        ]);
        $data = $request->all();
        $data['order_date'] = changeDate($request->order_date);
        $data['medication_date'] = changeDate($request->medication_date);
        $data['next_order_date'] = changeDate($request->next_order_date);
        $data['next_medication_date'] = changeDate($request->next_medication_date);
        Patient::create($data);

        if ($request->drug != "" && !is_null($request->drug[0])) {
            for ($i = 0; $i < count($request->drug); $i++) {
                if ($request->drug[$i] !== null && $request->quantity[$i] !== null) {
                    $medication = Medication::where('rx_id', $request->rx_id)->where('drug_name', $request->drug[$i])->get();
                    if (count($medication) > 0) {
                        $medication = Medication::where('rx_id', $request->rx_id)->where('drug_name', $request->drug[$i])->update([
                            'order_status' => $request->order_status[$i] ?? '0',
                        ]);
                    }
                }
            }
        }
        return redirect()->route('pharmacist.patient.index')->with('success_msg', 'New Order has been created.');
    }

    public function edit(Patient $patient)
    {
        $patient = $patient;
        //$rx = $patient->rx;
        $rx = Rx::withTrashed()->find($patient->rx_id);
        if (empty($rx)) {
            return response()->json(['error' => '1'], 200);
        }
        return view('pharmacist.patient.edit_render', get_defined_vars());
    }

    public function update(Request $request, Patient $patient)
    {
        request()->validate([
            'weeks' => 'required',
            'order_date' => 'required',
            'medication_date' => 'required',
        ]);
        $data = $request->except([
            '_token', '_method',
            'drug', 'quantity', 'order_status', 'checked_hidden'
        ]);
        $data['order_date'] = changeDate($request->order_date);
        $data['medication_date'] = changeDate($request->medication_date);
        $data['next_order_date'] = changeDate($request->next_order_date);
        $data['next_medication_date'] = changeDate($request->next_medication_date);
        Patient::where('id', $patient->id)->update($data);

        if ($request->drug != "" && !is_null($request->drug[0])) {
            for ($i = 0; $i < count($request->drug); $i++) {
                $medication = Medication::where('rx_id', $request->rx_id)->where('drug_name', $request->drug[$i])->get();
                if (count($medication) > 0) {
                    $medication = Medication::where('rx_id', $request->rx_id)->where('drug_name', $request->drug[$i])->update([
                        'order_status' => $request->order_status[$i] ?? "0",
                    ]);
                }
            }
        }
        return redirect()->route('pharmacist.patient.index')->with('success_msg', 'Patient has been updated.');
    }

    public function destroy($id)
    {
        $patients = Patient::find($id);
        $patients->delete();
        return redirect()->back()->with('success_msg', 'Patient has been deleted.');
    }

    public function withTrashed()
    {
        $patients = Patient::withTrashed()->where('pharmacy_id', Auth::user()->parent_id)->get();
        $with_trashed = 1;
        return view('pharmacist.patient.patient', get_defined_vars());
    }
}
