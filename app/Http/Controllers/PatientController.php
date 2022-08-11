<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Rx;
use Illuminate\Http\Request;
use Helpers\Helper\changeDate;
use App\Models\Medication;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index(Request $req)
    {
        $rxes = Rx::withTrashed()->get();
        //$rxes = Rx::doesntHave('patients')->withTrashed()->get();
        if ($req->pharmacy) {
            $patients = Patient::where('pharmacy_id', $req->pharmacy)->get();
            $pharmacy = User::find($req->pharmacy);
        } else {
            $tomorrow_date = Carbon::now()->addDay(1);
            $patients = Patient::whereDate('order_date', '>=', $tomorrow_date)->get();
        }
        return view('admin.patient.patient', get_defined_vars());
    }
    public function pharmacyManaged()
    {
    }
    public function order()
    {
        $date = date('Y-m-d');
        $orders = Patient::whereDate('next_order_date', '<=', $date)
            ->orWhereDate('order_date', '=', $date)
            ->orWhereDate('previous_order_date', '=', $date)
            ->orWhere([['order_date', '<=', $date], ['previous_order_date', '=', 'NULL']])
            ->get();
        //$rxes = Rx::doesntHave('patients')->withTrashed()->get();
        $rxes = Rx::withTrashed()->get();
        return view('admin.patient.order', get_defined_vars());
    }
    public function pendingOrder()
    {
        $date = date('Y-m-d');
        $orders = Patient::where('pendingOrder', 'true')->get();
        return view('admin.patient.pending_order', get_defined_vars());
    }
    public function completedOrder()
    {
        $orders = Patient::whereHas('rx', function ($q) {
            $q->whereHas('medications', function ($sq) {
                $sq->where('status', "1");
            });
        })->get();
        return view('admin.patient.completed_order', get_defined_vars());
    }
    public function store(Request $request)
    {
        request()->validate([
            'weeks' => 'required',
            'order_date' => 'required',
            'medication_date' => 'required',
        ]);
        $patients = Patient::where('rx_id', $request->rx_id)->first();
        /*if($patients)
        {
            return redirect()->route('patient.index')->with('error_msg', 'Order Already Exist.');
        }*/
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
                            'order_status' => $request->order_status[$i] ?? "0",
                        ]);
                    }
                }
            }
        }
        return redirect()->route('patient.index')->with('success_msg', 'New Order has been created.');
    }

    public function load_patient($id)
    {
        $rx = Rx::withTrashed()->find($id);
        if ($rx) {
            return view('admin.patient.add_render', get_defined_vars());
        } else {
            return response()->json(['error' => '1'], 200);
        }
    }
    public function edit(Patient $patient)
    {
        $patient = $patient;
        $rx = Rx::withTrashed()->find($patient->rx_id);
        if (empty($rx)) {
            return response()->json(['error' => '1'], 200);
        }
        return view('admin.patient.edit_render', get_defined_vars());
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

        return redirect()->route('patient.index')->with('success_msg', 'Patient has been updated.');
    }
    public function destroy($id)
    {
        $patients = Patient::find($id);
        $patients->delete();
        return redirect()->back()->with('success_msg', 'Patient has been deleted.');
    }
    public function withTrashed()
    {
        $patients = Patient::withTrashed()->get();
        $with_trashed = 1;
        return view('admin.patient.patient', get_defined_vars());
    }
}
