@extends('layouts.master')
@section('title', 'Staff | Order Pad')
@section('page-heading', 'Staff List')
@section('css')
@stop
{{-- Page Content Section --}}
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
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $staf)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area-pharmacy">
                    <div class="row-title">{{ $staf->name }}</div>
                    <div class="action-btn">
                        <form action="{{ route('staff.destroy',$staf->id) }}" method="POST">
                            <a href="{{ route('staff.edit',$staf->id) }}">Edit</a> |
                            @csrf
                            @method('DELETE')
                            <button class="text-red" type="submit" onclick="return confirm('Are you sure?')">Remove</button>
                        </form>
                    </div>
                </td>
                <td>{{ $staf->email }}</td>
                <td>{{$staf->created_at->diffForHumans()}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@stop
@section('js')
@stop