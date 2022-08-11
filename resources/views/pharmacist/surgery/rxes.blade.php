@extends('layouts.pharmacist')
@section('title', 'Surgery | Order Pad')
@section('page-heading', 'Surgeries')
@section('css')
@stop
{{-- Page Content Section --}}
@section('content')

<div class="text-right mb-3">
</div>
<div class="table-responsive">
    <table class="table table-hover datatables">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="30%">Surgery Title</th>
                <th width="30%">Email</th>
                <th width="30%">Address</th>
                <th>Created</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surgery as $surgery)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area">
                    <div class="row-title @if($surgery->rxes()->where('status', 1)->exists()) text-danger @elseif( $surgery->rxes()->where('date_needed_by',\Carbon\Carbon::today()->format('yyyy-m-d'))->exists()) text-warning @endif">{{ $surgery->name }}</div>
                    <div class="action-btn">
                        <form action="{{ route('surgery.destroy',$surgery->id) }}" method="POST">
                            <a href="{{ route('rx.collection') }}?surgery={{ $surgery->id }}">View RX</a> |
                            <a href="{{ route('surgery.edit',$surgery->id) }}">Edit</a> |
                            @csrf
                            @method('DELETE')
                            <button class="text-red" type="submit" onclick="return confirm('Are you sure?')">Remove</button>
                        </form>
                    </div>
                </td>
                <td>{{ $surgery->email ?? 'N / A' }}</td>
                <td>{{ $surgery->address ?? 'N / A' }}</td>
                <td>{{$surgery->created_at->diffForHumans()}}</td>
                <td class="surgery-notes">
                    @foreach($surgery->notes as $note)
                    <p><span class="font-weight-bold">{{ $loop->iteration }}:</span> {{ $note->notes }}</p>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
@section('js')
@stop