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
                    <div class="col-sm-6 text-left">Name: <span class="font-weight-bold">{{ $rx->name }}</span></div>
                    <div class="col-sm-6 text-right">DOB: <span class="font-weight-bold">{{ $rx->dob }}</span></div>
                </div>
              </div>
              <div class="card-body text-center">
                <h5 class="card-title">{{ $rx->email }}</h5>
                <p class="card-text">{{ $rx->comment }}</p>
              </div>
              <div class="card-footer text-muted text-center">
                RX Created: {{ $rx->created_at->diffForHumans() }}
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
              <div class="card-header font-weight-bold">
                Order Detail
              </div>
              <div class="card-body text-left">
                <h3 class="card-title">Order By: {{ $rx->order_by }}</h3>
                <h5 class="card-title">Order Date: {{ $rx->order_date }}</h5>
                <h5 class="card-title">Order Required: {{ $rx->date_needed_by }}</h5>
              </div>
              <div class="card-footer text-muted text-center">
                Send Via: {{ $rx->send_via }}
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
              <div class="card-header font-weight-bold">
                Medication Detail
              </div>
              <div class="card-body text-left">
                

                <table class="table table-bordered table-hover font-weight-bold">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th width="45%">Drug</th>
                      <th width="45%">Quantity</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach($rx->medications as $medication)
                        <tr>
                          <th>{{ $loop->iteration }}</th>
                          <td>{{ $medication->drug_name }}</td>
                          <td>{{ $medication->quantity }}</td>
                        </tr>
                    @endforeach

                    
                  </tbody>
                </table>

              </div>
            </div>
        </div>
    </div>

@stop



{{-- page JS Script Section --}}
@section('js')

@stop



