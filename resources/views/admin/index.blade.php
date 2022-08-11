@extends('layouts.master')
@section('title', 'Dashboard | Order Pad')
@section('page-heading', 'Dashboard')
@section('css')

@stop

@section('dashboard-bar')
<div class="header">
  <div class="container-fluid">
    <div class="header-body">

      <!-- Card stats -->
      <div class="row pt-5">
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Surgeries</h5>
                  <span class="h2 font-weight-bold mb-0">{{ count($surgeries) ?? '0'}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                    <i class="fas fa-syringe"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success font-weight-bold">{{ $surgeries_count ?? '0' }}</span>
                <span class="text-nowrap">for this month</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Pharmacies</h5>
                  <span class="h2 font-weight-bold mb-0">{{ count($pharmacies) }}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                    <i class="fas fa-notes-medical text-white"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success font-weight-bold">{{ $pharmacies_count ?? '0' }}</span>
                <span class="text-nowrap">for this month</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">RX</h5>
                  <span class="h2 font-weight-bold mb-0">{{ count($rxes) ?? '0' }}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                    <i class="fas fa-file-prescription"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success font-weight-bold">{{ $rxes_count ?? '0' }}</span>
                <span class="text-nowrap">for this month</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Patients</h5>
                  <span class="h2 font-weight-bold mb-0">{{ count($patients) }}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                    <i class="fas fa-calendar-alt"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success font-weight-bold">{{ $patient_count ?? '0' }}</span>
                <span class="text-nowrap">for this month</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('content')

<div class="text-left mb-3">
  <h3 class="mt-0">Recent Patients</h3>
</div>
<table class="table table-hover table-responsive mb-3 datatables">
  <thead>
    <tr>
      <th>#</th>
      <th>Patient</th>
      <th>Pharmacy</th>
      <th>Surgery</th>
      <th>Ordering Method</th>
      <th>Number Of Days On Rx</th>
      <th>Medication</th>
      <th>Notes</th>
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
          <form action="{{ route('patient.destroy',$patient->id) }}" method="POST">
            <a href="{{ route('patient.edit',$patient->id) }}">Edit</a> |
            @csrf
            @method('DELETE')
            <button class="text-red" type="submit" onclick="return confirm('Are you sure?')">Trash</button>
          </form>
        </div>
      </td>
      <td>{{ $patient->user->name }}</td>
      <td>{{ $patient->surgery->name }}</td>
      <td>{{ $patient->ordering_method }}</td>
      <td>{{ $patient->weeks }}</td>
      <td>{{ $patient->medication }}</td>
      <td>{{ $patient->notes }}</td>
      <td>{{ $patient->next_order_date!=='' ? date('d-m-Y', strtotime($patient->next_order_date)) : date('d-m-Y', strtotime($patient->order_date)) }}</td>
      <td>{{ $patient->next_medication_date!=='' ? date('d-m-Y', strtotime($patient->next_medication_date)) : date('d-m-Y', strtotime($patient->medication_date)) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@stop
{{-- page JS Script Section --}}
@section('js')

@stop