@extends('layouts.pharmacist')
@section('title', 'RX | Order Pad')
@section('page-heading', 'Patients')
@section('css')
@stop
@section('content')

<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('pharmacist.rx.create') }}?order={{0}}"> <i class="fa fa-plus"></i> Create New Patient</a>
</div>
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>DOB</th>
                <th>Surgery</th>
                <th>Pharmacy</th>
                <th>Scheduled</th>
                <th>Rxs in Sync?</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rxes as $rx)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td class="action-btn-area action-btn-area-rx">
                    <div class="row-title row-heading-width @if($rx->status == 1) text-danger @endif @if ( \Carbon\Carbon::today()->format('yyyy-m-d')  == $rx->date_needed_by) text-warning @endif">{{ $rx->name }}</div>
                    <div class="action-btn">
                        <a href="{{ route('pharmacist.edit.patient') }}?value={{$rx->name}}&rx_id={{$rx->id}}&order={{0}}" class="edit-rx">Edit</a> |
                        <button type="button" onclick="deleteAlert('{{ route('pharmacist.rx.delete',$rx->id) }}')" title="Delete" class="text-danger">Delete</button>

                    </div>
                </td>
                <td>{{ $rx->dob ?? ""}}</td>
                <td>{{ $rx->surgery->name ?? ""}}</td>
                <td>{{ $rx->user->name ?? ""}}</td>
                <td>
                    @if($rx->patients)
                    <span class="badge badge-info">Yes</span>
                    @else
                    <span class="badge badge-danger">No</span>
                    @endif
                </td>
                <td>
                    @if($rx->patients)
                    @if($rx->patients->count()==1)
                    <span class="badge badge-info">Yes</span>
                    @elseif($rx->patients->count() > 1)
                    <span class="badge badge-danger">No</span>
                    @else
                    <span class="badge badge-primary">N/A</span>
                    @endif
                    @else
                    <span class="badge badge-primary">N/A</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop