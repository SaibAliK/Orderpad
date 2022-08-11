<?php

namespace App\Http\Controllers\Pharmacist;

use App\Models\Rx;
use App\Models\Medication;
use App\Models\Surgery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Auth;
use Carbon\Carbon;
use PDF;
use App\Models\Drug;

use Illuminate\Support\Facades\Auth as FacadesAuth;

class RxController extends Controller
{
    public function index()
    {
        $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->get();
        return view('pharmacist.rx.rx', get_defined_vars());
    }
    public function create(Request $req)
    {
        if ($req->rxes_B == "true") {
            $drugs = Drug::all();
            $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->doesntHave('patients')->withTrashed()->get();
            return view('pharmacist.rx.add_edit_for_rxs', get_defined_vars());
        }

        if ($req->rx_id) {
            $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->withTrashed()->get();
        } else {
            $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->withTrashed()->get();
            //$rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->doesntHave('patients')->withTrashed()->get();
        }
        //$rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->
        //doesntHave('patients')->withTrashed()->get();
        //$rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->withTrashed()->get();
        $drugs = Drug::all();

        return view('pharmacist.rx.add-edit', get_defined_vars());
    }
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'surgery_id' => 'required',
        ]);
        if ($request->rx_id) {
            $rx = Rx::withTrashed()->find($request->rx_id);
            $rx->medications()->delete();
            $rx->restore();
            $rx = Rx::find($request->rx_id);
        } else {
            $rx = RX::withTrashed()->where('name', $request->name)->first();

            if ($rx) {
                $rxs = Rx::where('dob', $request->dob)->first();
                if ($rxs) {
                    return redirect()->back()->with('error_msg', 'Patient already exists!.');
                } else
                    return redirect()->back()->with('error_msg', 'Patient already exists!.');
            }
            $rx = new Rx();
        }
        if ($request->orderpad == 'false') {
            $rx->name = $request->name;
            $rx->dob = $request->dob;
            $rx->comment = $request->comment;
            $rx->date_needed_by = $request->date_needed;
            $rx->surgery_id = $request->surgery_id;
            $rx->pharmacy_id = Auth::user()->parent_id;
            $rx->save();
            if ($request->drug != "" && !is_null($request->drug[0])) {
                for ($i = 0; $i < count($request->drug); $i++) {
                    if ($request->drug[$i] !== null  && $request->quantity[$i] !== null) {
                        $medication = new Medication();
                        $medication->rx_id = $rx->id;
                        $medication->drug_name = $request->drug[$i];
                        $medication->quantity = $request->quantity[$i];
                        $medication->order_status = $request->checked_hidden[$i] ?? "0";
                        $medication->is_checked = $request->checked_hidden[$i] ?? 0;
                        $medication->save();
                    }
                }
            }
            if (empty($request->rx_id))
                $rx->delete();
            if ($request->orderpad == 'false') {
                return redirect()->route('pharmacist.rx.full_list')->with('success_msg', 'New Patient has been created.');
            }
            return redirect()->route('pharmacist.rx.full_list')->with('success_msg', 'New Rx has been created.');
        } else {
            $rx->name = $request->name;
            $rx->dob = $request->dob;
            $rx->surgery_id = $request->surgery_id;
            $rx->order_by = $request->order_by;
            $rx->order_date = $request->order_date;
            $rx->date_needed_by = $request->date_needed;
            $rx->pharmacy_id = Auth::user()->parent_id;
            $rx->send_via = $request->send_via;
            $rx->other_ordering_method = $request->other_ordering_method ?? "";
            $rx->comment = $request->comment;
            if ($request->today == '1') {
                $rx->today = "1";
                $rx->order_today = date('Y-m-d H:i:s');
            }
            $rx->status = 0;
            $rx->save();
            // save drugs
            if ($request->approve == 'true') {
                $patient = Patient::find($request->patient_id);
                $gapNow = 0;
                if ((int) $patient->order_gap_status !== 0) {
                    $x = (int) $patient->order_gap_achieved;
                    $y = (int) $patient->order_gap_now;
                    $gapNow = $x + $y;
                    $z = (int) $patient->order_gap;
                    $gStatus = 1;
                    if ($gapNow > $z) {
                        for ($i = $x; $i > 0; $i--) {
                            $gapNow = $y + $i;
                            if ($gapNow == $z) {
                                $x = $i;
                                $i = $x;
                            }
                        }
                        $patient['order_gap_status'] = 0;
                        $patient['order_gap'] = 0;
                        $patient['order_gap_achieved'] = 0;
                        $patient['order_gap_now'] = 0;
                    } else {
                        $patient['order_gap_now'] = $gapNow;
                    }
                    $order_date = date('Y-m-d', strtotime($patient->next_order_date . ' + ' . ($patient->weeks - $x) . ' days'));
                    $medication_date = date('Y-m-d', strtotime($patient->next_medication_date . ' + ' . $patient->weeks . ' days'));
                } else {
                    $order_date = date('Y-m-d', strtotime($patient->next_order_date . ' + ' . $patient->weeks . ' days'));
                    $medication_date = date('Y-m-d', strtotime($patient->next_medication_date . ' + ' . $patient->weeks . ' days'));
                }
                $patient['pendingOrder'] = "true";
                $patient['pending_status_date'] = date('Y-m-d H:i:s');
                $patient['previous_order_date'] = $patient->order_date;
                $patient['previous_medication_date'] = $patient->medication_date;
                $patient['order_date'] = $patient->next_order_date;
                $patient['medication_date'] = $patient->next_medication_date;
                $patient['next_order_date'] = $order_date;
                $patient['next_medication_date'] = $medication_date;
                $patient->save();
            }
            if ($request->drug != "" && !is_null($request->drug[0])) {
                for ($i = 0; $i < count($request->drug); $i++) {
                    if ($request->drug[$i] !== null  && $request->quantity[$i] !== null) {
                        $medication = new Medication();
                        $medication->rx_id = $rx->id;
                        $medication->drug_name = $request->drug[$i];
                        $medication->quantity = $request->quantity[$i];
                        $medication->order_status = $request->checked_hidden[$i] ?? "0";
                        $medication->is_checked = $request->checked_hidden[$i] ?? 0;
                        $medication->save();
                    }
                }
            }
        }
        if (empty($request->orderpad)) {
            return redirect()->route('pharmacist.rx.full_list')->with('success_msg', 'Patient update successfully');
        }
        return redirect()->route('pharmacist.rx.collection')->with('success_msg', 'New RX has been created.');
    }

    public function saveUncollectedRx(Request $request)
    {
        //dd($request->all());
        request()->validate([
            'name' => 'required',
            'surgery_id' => 'required',
        ]);
        $rx = RX::withTrashed()->where('name', $request->name)->first();
        if ($rx) {
            return redirect()->back()->with('success_msg', 'RX already exist.');
        }
        $rx = new Rx();
        $rx->name = $request->name;
        $rx->dob = $request->dob;
        $rx->surgery_id = $request->surgery_id;
        $rx->pharmacy_id = $request->pharmacy_id;
        $rx->pharmacy_id = Auth::user()->parent_id;
        $rx->order_by = $request->order_by;
        $rx->order_date = $request->order_date;
        $rx->date_needed_by = $request->date_needed;
        $rx->comment = $request->comment;

        $rx->save();
        if ($request->drug != "" && !is_null($request->drug[0])) {
            for ($i = 0; $i < count($request->drug); $i++) {
                if ($request->drug[$i] !== null && $request->quantity[$i] !== null) {
                    $medication = new Medication();
                    $medication->rx_id = $rx->id;
                    $medication->drug_name = $request->drug[$i];
                    $medication->quantity = $request->quantity[$i];
                    $medication->order_status = $request->checked_hidden[$i] ?? "0";
                    $medication->is_checked = $request->checked_hidden[$i] ?? "0";
                    $medication->save();
                }
            }
        }
        if ($request->unknown_drug != "" && !is_null($request->unknown_drug[0])) {
            for ($i = 0; $i < count($request->unknown_drug); $i++) {
                if ($request->unknown_drug[$i] !== null && $request->unknown_quantity[$i] !== null) {
                    $medication = new Medication();
                    $medication->rx_id = $rx->id;
                    $medication->drug_name = $request->unknown_drug[$i];
                    $medication->quantity = $request->unknown_quantity[$i];
                    $medication->unknown_med = 1;
                    $medication->order_status = '0';
                    $medication->is_checked =  '0';
                    $medication->save();
                }
            }
        }
        return redirect()->route('pharmacist.rx.collection')->with('success_msg', 'New RX has been created.');
    }

    public function reorder(Request $request)
    {
        //dd($request->all());
        $patient = Patient::find($request->patient_id)->replicate();
        $rx = Rx::find($patient->rx_id)->replicate();
        $rx->order_today = null;
        $rx->order_date = $request->order_date;
        $rx->date_needed_by = $request->date_needed;
        $rx->order_today = date('Y-m-d H:i:s');

        $rx->collected_at = null;
        $rx->today = "0";
        $rx->send_via = null;
        $rx->save();

        $medications = Medication::where('rx_id', $patient->rx_id)->get();
        if (count($medications) > 0) {
            foreach ($medications as $value) {
                $meds = Medication::find($value->id)->replicate();
                $meds->status = '0';
                $meds->rx_id = $rx->id;
                $meds->save();
            }
        }

        $new_date = Carbon::parse($patient->pending_status_date)->addDays(28);
        $patient->order_date = $new_date;

        /*$patient->order_date = "";
        $patient->medication_date = "";
        $patient->next_order_date = "";
        $patient->previous_medication_date = "";
        $patient->next_medication_date = "";*/

        $patient->pendingOrder = "false";
        $patient->pending_status_date = null;
        $patient->rx_id = $rx->id;
        $patient->save();
        return redirect()->back()->with('success_msg', 'Re Order created successfully.');
    }
    public function urgentRX(Request $request, $id)
    {
        if ($request->id) {
            $rx = RX::withTrashed()->find($request->id);
            if ($rx) {
                if ($request->value == "urgent") {
                    $rx->status = 0;
                } else {
                    $rx->status = 1;
                }
                $rx->save();
            } else
                dd("not found");
        } else {
        }
    }

    public function save_collected_note(Request $request)
    {
        if ($request->task == "delete") {
            $rx = Rx::find($request->rx_id);
            $rx->note = "";
            $rx->save();
        }
        if ($request->task == "update") {
            $rx = Rx::find($request->rx_id);
            $rx->note = $request->note;
            $rx->save();
        }
        $rx = Rx::find($request->rx_id);
        $rx->note = $request->note;
        $rx->save();
    }

    public function show($rx)
    {
        //return view('render.view_rx', get_defined_vars());
        $rx = Rx::withTrashed()->find($rx);
        //dd($rx->name);
        if ($rx->patients && !empty($rx->collected_at)) {
            return view('render.view_rx_collected', get_defined_vars());
        } else
            return view('render.view_rx_un_ollected', get_defined_vars());
    }
    public function edit($id)
    {
        $rx = Rx::withTrashed()->find($id);
        if (empty($rx)) {
            return redirect()->back()->with('error_msg', 'Rx is not exist');
        }
        $drugs = Drug::all();

        return view('pharmacist.rx.add-edit', get_defined_vars());
    }
    public function update(Request $request, Rx $rx)
    {
        if ($rx) {
            $rxes = Rx::find($request->rx->id);
            if (empty($rxes)) {
                return redirect()->back()->with('error_msg', 'rx is not exist');
            }
            $rxes->name = $request->name;
            $rxes->dob = $request->dob;
            $rxes->surgery_id = $request->surgery_id;
            $rxes->save();
            if ($request->drug != "" && !is_null($request->drug[0])) {
                for ($i = 0; $i < count($request->drug); $i++) {
                    $medication = new Medication();
                    $medication->rx_id = $rxes->id;
                    $medication->drug_name = $request->drug[$i];
                    $medication->quantity = $request->quantity[$i];
                    $medication->is_checked = $request->checked_hidden[$i];
                    $medication->save();
                }
            }
        }
        return redirect()->route('pharmacist.rx.full_list');
    }

    public function destroy($id)
    {
        $rx = Rx::find($id);
        $rx->delete();
        return redirect()->route('pharmacist.rx.collection')->with('success_msg', 'RX has been deleted.');
    }
    public function delete($id)
    {
        $rx = Rx::withTrashed()->find($id);
        if (empty($rx)) {
            return redirect()->back()->with('error_msg', 'rx is not found');
        }
        $rx->forceDelete();
        return redirect()->back()->with('success_msg', 'Patient has been deleted.');
    }

    public function patientEdit(Request $req)
    {
        $approve = "";
        $patient_id = '';
        $today = "";
        $drugs = Drug::all();
        if ($req->rx_id) {
            $rx = Rx::withTrashed()->where('id', $req->rx_id)->first();
            return view('pharmacist.rx.add-edit-patient', get_defined_vars());
        }
    }

    public function rxCollection(Request $req)
    {
        $current_date = Carbon::now()->format('d-m-Y');
        $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id);
        $rxes = $rxes->whereHas('medications', function ($query) {
            $query->where('status', '0');
        });
        if ($req->surgery) {
            $rxes = $rxes->where('surgery_id', $req->surgery)->get();
            $surgery_name = Surgery::find($req->surgery)->name;
        } else {
            $rxes = $rxes->get();
        }
        return view('pharmacist.rx.collection', get_defined_vars());
    }

    public function rxCollected()
    {
        $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id)->get();
        return view('pharmacist.rx.collected', get_defined_vars());
    }

    public function rxAllMedicationCollect(Request $request, $id)
    {
        Medication::where('rx_id', $id)->update(['status' => '1']);
        Rx::withTrashed()->where('id', $id)->update(['status' => 0, 'collected_at' => date('Y-m-d H:i:s')]);

        //this will replicate the order and updated_code
        /*if ($request->order_id) {
            $patient = Patient::find($request->order_id)->replicate();

            $rx = Rx::find($id)->replicate();
            $rx->order_today = null;
            $rx->collected_at = null;
            $rx->today = "0";
            $rx->send_via = null;
            $rx->save();

            $medications = Medication::where('rx_id', $id)->get();
            if (count($medications) > 0) {
                foreach ($medications as $value) {
                    $meds = Medication::find($value->id)->replicate();
                    $meds->status = '0';
                    $meds->rx_id = $rx->id;
                    $meds->save();
                }
            }
            $pre_span = $patient->weeks;
            $pre_order_date = Carbon::parse($patient->previous_order_date)->addDays($pre_span);
            $pre_med_date = Carbon::parse($patient->previous_medication_date)->addDays($pre_span);

            //$new_date = Carbon::parse($patient->pending_status_date)->addDays(28);

            $patient->order_date = $pre_order_date;
            $patient->medication_date = $pre_med_date;
            $patient->previous_order_date = null;
            $patient->next_order_date = $patient->next_order_date;
            $patient->next_medication_date = $patient->next_medication_date;
            $patient->previous_medication_date = null;
            $patient->pendingOrder = "false";
            $patient->pending_status_date = null;
            $patient->rx_id = $rx->id;
            $patient->save();
        }*/

        return back()->with('success_msg', 'All medications of RX has been collected.');
    }

    public function newPopup(Request $request)
    {
        if ($request->popup == "third") {
            if ($request->order_id && $request->rx_id) {
                $patient = Patient::find($request->order_id)->replicate();

                $rx = Rx::withTrashed()->find($request->rx_id)->replicate();
                $rx->order_today = null;
                $rx->collected_at = null;
                $rx->collected_rx = null;
                $rx->order_by = Auth::user()->name;
                $rx->today = "0";
                $rx->send_via = null;
                $rx->save();

                $medications = Medication::where('rx_id', $request->rx_id)->get();
                if (count($medications) > 0) {
                    foreach ($medications as $value) {
                        $meds = Medication::find($value->id)->replicate();
                        $meds->status = '0';
                        $meds->rx_id = $rx->id;
                        $meds->save();
                    }
                }

                $pre_span = $patient->weeks;

                $change_order = changeDate($request->next_order_date);
                $change_meds = changeDate($request->next_meds_start_date);
                //$pre_order_date = Carbon::parse($change_order)->addDays($pre_span);
                //$pre_med_date = Carbon::parse($change_meds)->addDays($pre_span);

                $patient->order_date = $change_order;
                $patient->medication_date = $change_meds;
                $patient->previous_order_date = null;
                //$patient->next_order_date = $pre_order_date;
                //$patient->next_medication_date = $pre_med_date;
                $patient->previous_medication_date = null;
                $patient->pendingOrder = "false";
                $patient->pending_status_date = null;
                $patient->rx_id = $rx->id;
                $patient->save();
            }
        } else {

            $rx = RX::withTrashed()->find($request->rx_id);
            $rx->collected_rx = changeDate($request->collected_date);
            $rx->save();

            $patients = Patient::find($request->order_id);
            $patients->previous_medication_date  = changeDate($request->meds_start_date);
            $patients->save();

            $span = $patients->weeks;
            $new_order_date = Carbon::parse($patients->previous_order_date)->addDays($span);
            $new_med_date = Carbon::parse($patients->previous_medication_date)->addDays($span);
            $next_order_date =  Carbon::createFromFormat('Y-m-d H:i:s', $new_order_date)->format('d-m-Y');
            $next_meds_date = Carbon::createFromFormat('Y-m-d H:i:s', $new_med_date)->format('d-m-Y');
            return response()->json(['next_order' => $next_order_date, 'next_medication' => $next_meds_date]);
        }
    }

    public function rxPopupMedicationCollection(Request $request)
    {
        $order_id = $request->order_id;
        $medications = Medication::where(['rx_id' => $request->id, 'status' => '0'])->get();
        if (count($medications) < 1) {
            return response()->json(['error' => '1'], 200);
        } else {
            $html = view('render.medications', get_defined_vars())->render();
            return response()->json(['html' => $html], 200);
        }
    }

    public function rxPopupMedicationCollectionUpdate(Request $request)
    {
        //dd($request->all());
        if (isset($request->close_type) && $request->close_type == "second") {
            foreach ($request->medications as $item) {
                $meds_id = explode(',', $item);
            }

            for ($i = 0; $i < count($meds_id); $i++) {
                Medication::where('id', $meds_id[$i])->update(['status' => '0']);
            }
            //Medication::where('rx_id', $request->rx_id)->whereIn('id', $request->medications)->update(['status' => '0']);

            $meds = Medication::where([
                'rx_id' => $request->rx_id,
                'status' => '1'
            ])->count();

            if (!$meds)
                Rx::where('id', $request->rx_id)->update(['status' => 0, 'collected_at' => null]);
        } elseif (isset($request->close_type) && $request->close_type == "third") {
            //cancel the first step
            foreach ($request->medications as $item) {
                $meds_id = explode(',', $item);
            }

            for ($i = 0; $i < count($meds_id); $i++) {
                Medication::where('id', $meds_id[$i])->update(['status' => '0']);
            }

            $meds = Medication::where([
                'rx_id' => $request->rx_id,
                'status' => '1'
            ])->count();

            if (!$meds)
                Rx::where('id', $request->rx_id)->update(['status' => 0, 'collected_at' => null]);

            //cancel the second step
            $rx = RX::withTrashed()->find($request->rx_id);
            $rx->collected_rx = null;
            $rx->save();

            $patients = Patient::find($request->order_id);
            $patients->previous_medication_date  = null;
            $patients->save();
        } else {
            Medication::where('rx_id', $request->rx_id)->whereIn('id', $request->medications)->update(['status' => '1']);
            $meds = Medication::where([
                'rx_id' => $request->rx_id,
                'status' => '0'
            ])->count();

            if (!$meds)
                Rx::where('id', $request->rx_id)->update(['status' => 0, 'collected_at' => date('Y-m-d H:i:s')]);
        }
        return response()->json(['medications' => $request->medications]);
        //return back()->with('success_msg', 'Partially medications of RX has been collected.');
    }

    public function loadRxPatient(Request $request)
    {
        $approve = $request->approve;
        $patient_id = $request->patient_id;
        $today = $request->today;
        if (empty($request->patient_id) && !empty($request->value)) {
            $rx = Rx::withTrashed()->where('pharmacy_id', Auth::user()->parent_id)
                ->where('id', $request->value)
                ->first();

            if (is_null($rx)) {
                return response()->json(['error' => '1'], 200);
            } else {
                $drugs = Drug::all();
                $html = view('pharmacist.rx.add-edit-render_patient', get_defined_vars())->render();
                return response()->json(['html' => $html], 200);
            }
        } else {
            $rx = Rx::withTrashed()->where('pharmacy_id', Auth::user()->parent_id)
                ->where('id', $request->value)
                ->first();

            if (is_null($rx)) {
                return response()->json(['error' => '1'], 200);
            } else {
                $drugs = Drug::all();
                $html = view('pharmacist.rx.add-edit-render', get_defined_vars())->render();
                return response()->json(['html' => $html], 200);
            }
        }
    }

    public function withTrashed()
    {
        $rxes = Rx::where('pharmacy_id', Auth::user()->parent_id);
        $rxes = $rxes->get();
        /*$rxes = $rxes->whereHas('medications', function ($query) {
            $query->where('status', '0');
        })->get();*/
        $with_trashed = 1;
        return view('pharmacist.rx.full_list', get_defined_vars());
    }

    public function noturgentRX($id)
    {
        $rx = RX::find($id);
        if (empty($rx)) {
            return redirect()->back()->with('error_msg', 'rx is not exist');
        }
        $rx->status = 0;
        $rx->save();
        return redirect()->back()->with('success_msg', 'RX updated successfully.');
    }
    public function faxes()
    {
        $current_date = Carbon::now();
        $rxes = Rx::where('send_via', 'fax')->where('today', '1')->get();
        return view('pharmacist.rx.faxes', get_defined_vars());
    }
    public function showFax($id)
    {
        $rx = Rx::withTrashed()->find($id);
        if (empty($rx)) {
            return redirect()->back()->with('error_msg', 'Faxes is not exist');
        }
        return view('pharmacist.rx.faxShow', get_defined_vars());
    }
    public function exportPdf(Request $request)
    {
        if ($request->surgery_id) {
            $surgery = Surgery::find($request->surgery_id);
        }

        if ($request->db_data == "true") {
            $current_date = Carbon::now();
            $pdf = PDF::loadView('exports.fax_db', get_defined_vars());
            return $pdf->download('fax.pdf');
        }
        $current_date = Carbon::now();
        $pdf = PDF::loadView('exports.faxes', get_defined_vars());
        return $pdf->download('fax.pdf');
    }
    public function loadOrder(Request $request)
    {
        if ($request->surgery_id) {
            $surgery = Surgery::find($request->surgery_id);
        }
        if (isset($request->email)) {
            $rx = Rx::withTrashed()->find($request->rx_id);
            if ($rx) {
                return view('pharmacist.rx.load_email_order', get_defined_vars());
            }
        } else {
            $rx = Rx::find($request->rx_id);
            //$request->all();
            return view('pharmacist.rx.loadOrder', get_defined_vars());
        }
    }
    public function loadOrderNew(Request $request)
    {
        if ($request->surgery_id) {
            if ($request->email) {
                $surgery = Surgery::find($request->surgery_id);
                if ($surgery) {
                    return view('render.load_email_order', get_defined_vars());
                }
            } else {
                $surgery = Surgery::find($request->surgery_id);
                return view('render.pharmacy_loadOrder', get_defined_vars());
            }
        } else {
            return "Please Select Surgery";
        }
    }
}
