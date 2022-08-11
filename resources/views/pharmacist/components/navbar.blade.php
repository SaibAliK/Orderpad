<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom {{empty($_GET['order']) ? 'bg-primary' : 'bg-danger'}}">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <h1 class="page-heading">@yield('page-heading')</h1>
      <ul class="navbar-nav align-items-center mr-auto">
      </ul>
      <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
        @if(Session::has('admin_auth_id'))
        <a href="{{ route('pharmacist.main.branch.login') }}" class="btn btn-sm btn-success">Go to Main Branch</a>
        @endif

        <li class="nav-item dropdown">
          <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="{{ asset('images/default.png') }}">
              </span>
            </div>
          </a>
          <div class="dropdown-menu  dropdown-menu-right ">
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Hello!</h6>
            </div>
            <a href="{{ route('profile') }}" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('staff.logout') }}" class="text-center">
              @csrf
              <x-jet-responsive-nav-link class="dropdown-item" href="{{ route('staff.logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                {{ __('Logout') }}
              </x-jet-responsive-nav-link>
            </form>
          </div>
        </li>
        <li class="nav-item d-xl-none ml-3">
          <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>