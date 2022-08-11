@extends('layouts.master')
@section('title', 'Staff | Order Pad')
@section('page-heading', 'Staff List')
@section('css')
@stop
@section('content')

<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('staff.create') }}"> <i class="fa fa-plus"></i> Create New Member</a>
</div>
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>Member Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $staf)
            @if($staf->role !== "admin")
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area-pharmacy">
                    <div class="row-title">{{ $staf->name ?? ""}}</div>
                    <div class="action-btn">
                        <a href="{{ route('staff.edit',$staf->id) }}">Edit</a> |
                        <button type="button" onclick="deleteAlert('{{ route('staff.destroy',$staf->id) }}')" title="Delete" class="text-danger">Delete</button>
                    </div>
                </td>
                <td>{{ $staf->email ?? ""}}</td>
                <td>{{ $staf->role ?? "" }}</td>
                <td>{{$staf->created_at->diffForHumans()}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@stop
@section('js')
@stop