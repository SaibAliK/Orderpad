<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white">
  <div class="scrollbar-inner">
    <!-- Brand -->
    <div class="sidenav-header pl-4 pt-3">
      <img src="{{ asset('/RX-orderpad-logo.jpg') }}" class="navbar-brand-img" alt="" width="150px;height:180px">
      <p class="text-success font-weight-bold mt-2" style="margin-left: -43px !important">Admin Dashboard</p>
    </div>
    <div class="navbar-inner">
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/surgery*') || Route::currentRouteName() == 'admin/surgery*') ? 'collapse' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#surgery" aria-expanded="false">
              <i class="fas fa-syringe text-orange pt-1"></i>
              <span class="nav-link-text">Surgeries</span>
            </a>
            <div id="surgery" class="collapse {{ (Route::currentRouteName() == 'admin/surgery*' || Route::currentRouteName() == 'order') ? 'show' : '' }}" style="">
              <ul class="wraplist ml-4">
                <li class="nav-item">
                  <a class="nav-link {{ (request()->is('urgent_collection')) ? 'active' : '' }}" href="{{route('urgent_collection')}}">
                    <span class="nav-link-text">Urgent Collections</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ (request()->is('surgery.all_collection')) ? 'active' : '' }}" href="{{route('surgery.all_collection')}}">
                    <span class="nav-link-text">All Collections</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link {{ (request()->is('surgery.index')) ? 'active' : '' }}" href="{{ route('surgery.index') }}">
                    <span class="nav-link-text">All Surgeries</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/pharmacy*')) ? 'active' : '' }}" href="{{ route('pharmacy.index') }}">
              <i class="fas fa-notes-medical text-primary"></i>
              <span class="nav-link-text">Pharmacies</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/staff*')) ? 'active' : '' }}" href="{{ route('staff.index') }}">
              <i class="fas fa-notes-medical text-primary"></i>
              <span class="nav-link-text">Staff</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/rx*')) ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#order-nav1" aria-expanded="false">
              <i class="fas fa-file-prescription text-info"></i>
              <span class="nav-link-text">Collection List</span>
            </a>
            <div id="order-nav1" class="collapse {{ (request()->is('admin/rx*')) ? 'active' : '' }}">
              <ul class="wraplist ml-4">

                <li class="nav-item">
                  <a href="{{ route('rx.collection') }}" class="nav-link">
                    <span class="nav-link-text">Uncollected Rxs</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('rx.collected') }}" class="nav-link">
                    <span class="nav-link-text">Collected Rxs</span>
                  </a>
                </li>

              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/rx/create')) ? 'active' : '' }}" href="{{ route('rx.create') }}?value=&order=1">
              <i class="fas fa-plus text-success"></i>
              <span class="nav-link-text">Orderpad</span>
            </a>
          </li>
          {{--<li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/rx/faxes')) ? 'active' : '' }}" href="{{ route('faxes') }}">
              <i class="fa fa-print" aria-hidden="true"></i>
              <span class="nav-link-text">Faxes</span>
            </a>
          </li>--}}
          <li class="nav-item">
            <a class="nav-link {{ (request()->is('admin/patient*') || Route::currentRouteName() == 'order') ? 'collapse' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#order-nav" aria-expanded="false">
              <i class="ni ni-check-bold text-success"></i>
              <span class="nav-link-text">Order Schedule</span>
            </a>
            <div id="order-nav" class="collapse {{ (Route::currentRouteName() == 'patient.index' || Route::currentRouteName() == 'order') ? 'show' : '' }}" style="">
              <ul class="wraplist ml-4">
                {{--<li class="nav-item">
                  <a href="{{route('pharmacy.managed')}}" class="nav-link  {{ Route::currentRouteName() == 'pharmacy.managed' ? 'active' : '' }}">
                    <span class="nav-link-text">Pharmacy Managed Patients</span>
                  </a>
                </li>--}}
                <li class="nav-item">
                  <a href="{{ route('order') }}" class="nav-link  {{ Route::currentRouteName() == 'order' ? 'active' : '' }}">
                    <span class="nav-link-text">Today's Orders</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('patient.index') }}" class="nav-link {{ Route::currentRouteName() == 'patient.index' ? 'active' : '' }}">
                    <span class="nav-link-text">Future Orders</span>
                  </a>
                </li>
                {{--<li class="nav-item">
                  <a href="{{route('pendingOrder')}}" class="nav-link {{ Route::currentRouteName() == 'patient.pendingOrder' ? 'active' : '' }}">
                    <span class="nav-link-text">Pending Orders</span>
                  </a>
                </li>--}}
                {{--<li class="nav-item">
                  <a href="{{route('completedOrder')}}" class="nav-link {{ Route::currentRouteName() == 'completedOrder' ? 'active' : '' }}">
                    <span class="nav-link-text">Completed Orders</span>
                  </a>
                </li>--}}
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a href="{{ route('rx.full_list') }}" class="nav-link {{ Route::currentRouteName() == 'rx.full_list' ? 'active' : '' }}">
              <i class="fas fa-notes-medical text-primary"></i>
              <span class="nav-link-text">Patients</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>