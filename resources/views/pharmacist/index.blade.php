@extends('layouts.pharmacist')
@section('title', 'Pharmaist Dashboard | Order Pad')
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
                  <span class="h2 font-weight-bold mb-0">{{ count($pharmacies) ?? '0'}}</span>
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
                  <h5 class="card-title text-uppercase text-muted mb-0">Orderes</h5>
                  <span class="h2 font-weight-bold mb-0">{{ count($patients) ?? '0'}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                    <i class="fas fa-calendar-alt"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success font-weight-bold">{{ $order_count ?? '0' }}</span>
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
  <h3 class="mt-0">Recent Orders</h3>
</div>


@stop

{{-- page JS Script Section --}}
@section('js')

@stop