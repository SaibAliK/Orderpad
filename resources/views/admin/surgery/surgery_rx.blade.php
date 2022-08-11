@extends('layouts.master')
@section('title', 'RX Detail | Order Pad')
@section('page-heading', 'RX Detail')
@section('css')
@stop
{{-- Page Content Section --}}
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">

        <div class="row">
          <div class="col-sm-6 text-left">Surgery Name: <span class="font-weight-bold">{{ $surgery->name }}</span></div>
        </div>
      </div>
      <div class="card-body text-center">
        <h5 class="card-title">{{ $surgery->email }}</h5>
        <h4>Address: <span class="font-weight-bold">{{ $surgery->address }}</span></h4>
      </div>
      <div class="card-footer text-muted text-center">
        Surgery Created: {{ $surgery->created_at->diffForHumans() }}
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">
      <div class="card-header font-weight-bold">
        Surgery RX List
      </div>
      <div class="card-body text-left table-responsive">
        <table class="table table-bordered table-hover table-sm font-weight-bold">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>DOB</th>
              <th>Date Needed By</th>
              <th>Send Via</th>
              <th>Comment</th>
            </tr>
          </thead>
          <tbody>

            @foreach($surgery->rxes as $rx)
            <tr>
              <th>{{ $loop->iteration }}</th>
              <td>{{ $rx->name }}</td>
              <td>{{ $rx->dob }}</td>
              <td>{{ $rx->date_needed_by }}</td>
              <td>{{ $rx->send_via }}</td>
              <td>{{ $rx->comment }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@stop

@section('js')

@stop