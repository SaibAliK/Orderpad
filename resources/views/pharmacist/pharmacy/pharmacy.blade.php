@extends('layouts.pharmacist')
@section('title', 'Pharmacy | Order Pad')
@section('page-heading', 'Pharmacies')
@section('css')
@stop
@section('content')

<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('pharmacist.pharmacy.create') }}"> <i class="fa fa-plus"></i> Create New Pharmacy</a>
</div>
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>Pharmacy Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pharmacists as $pharmacist)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area-pharmacy">
                    <div class="row-title">{{ $pharmacist->name }}</div>
                    <div class="action-btn">
                        <form action="{{ route('pharmacist.pharmacy.destroy',$pharmacist->id) }}" method="POST">
                            <a href="{{ route('pharmacist.pharmacy.edit',$pharmacist->id) }}">Edit</a> |
                            @csrf
                            @method('DELETE')
                            <button class="text-red" type="submit" onclick="return confirm('Are you sure?')">Remove</button>
                        </form>
                    </div>
                </td>
                <td>{{ $pharmacist->email }}</td>
                <td>{{ $pharmacist->pharmacyProfile->phone ?? 'N / A' }}</td>
                <td>{{ $pharmacist->pharmacyProfile->address ?? 'N / A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
@section('js')
@stop