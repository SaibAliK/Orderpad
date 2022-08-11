@extends('layouts.master')
@section('title', 'Order | Order Pad')
@section('page-heading', 'Pharmacy Managed Patient')
@section('css')
@stop
@section('content')
<div>
    <div>
        <div class="table-responsive">
            <table class="table table-hover mb-3 datatables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>DOB</th>
                        <th>Rx Span</th>
                        <th>No of Meds</th>
                        <th>Ordering Method</th>
                        <th>Order Status</th>
                        <th>Notes</th>
                        <th>Pharmacy</th>
                        <th>Surgery</th>
                        <th>Order Date</th>
                        <th>Medication Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td class="action-btn-area">
                            <div class="row-title">{{ $patient->patient }}</div>
                            <div class="action-btn">

                            </div>
                        </td>
                        <td>{{$patient->rx->dob ?? ""}}</td>
                        <td>{{ $patient->weeks }}</td>
                        <td>
                            {{ $patient->medications() }}
                        </td>
                        <td>{{ $patient->ordering_method }}</td>
                        <td>
                            @if($patient->pendingOrder=="true" && !empty($patient->pending_status_date) && empty($patient->rx->collect_at))
                            <span class="badge badge-info">Pending</span>
                            @elseif(!empty($patient->rx->collected_at))
                            <span class="badge badge-success">Comleted</span>
                            @else
                            <span class="badge badge-primary">N / A</span>
                            @endif
                        </td>
                        <td>{{ $patient->notes }}</td>
                        <td>{{ $patient->user->name }}</td>
                        <td>{{ $patient->surgery->name }}</td>
                        <td>{{ $patient->next_order_date!=='' ? date('d-m-Y', strtotime($patient->order_date)) :  date('d-m-Y', strtotime($patient->next_order_date)) }}</td>
                        <td>{{ $patient->next_medication_date!=='' ? date('d-m-Y', strtotime($patient->medication_date)) :  date('d-m-Y', strtotime($patient->next_medication_date)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @stop
    @section('js')
    <script type="text/javascript">

    </script>
    @stop