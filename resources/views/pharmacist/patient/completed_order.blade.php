@extends('layouts.pharmacist')
@section('title', 'Order | Order Pad')
@section('page-heading', 'Completed Orders')
@section('css')
@stop
@section('content')
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>Patient</th>
                <th>DOB</th>
                <th>Rx Span</th>
                <th>No of Meds</th>
                <th>Order completed On</th>
                <th>Medication Date</th>
                <th>Order Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            @if(!empty($order->rx->collected_at))
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="action-btn-area">
                    <div class="row-title">{{ $order->patient ?? ""}}</div>
                </td>
                <td>{{$order->rx->dob ?? ""}}</td>
                <td>{{ $order->weeks ?? ""}}</td>

                <td>
                    @if( empty($order->allMeds()) && empty($order->orderedMeds()) )
                    N / A
                    @else
                    {{ $order->allMeds()." / ".$order->orderedMeds() ?? ""}}
                    @endif
                </td>
                <td>{{ date('d-m-Y',strtotime($order->rx->collected_at)) ?? " N / A"}}</td>
                <td>{{ date('d-m-Y', strtotime($order->previous_medication_date)) ?? 'N/A'}}</td>
                <td>
                    <span class="badge badge-success">Completed</span>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@stop
@section('js')
@stop