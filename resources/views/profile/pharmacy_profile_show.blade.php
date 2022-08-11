@extends('layouts.pharmacist')
@section('title', 'Profile Page | Order Pad')
@section('page-heading', 'Profile Info')
@section('css')
@stop
@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row">
    </div>
    <div class="content-body">
      <section class="users-edit">
        <div class="">
          <div class="card-content">
            <div class="card-body">
              <div class="">
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                  <div class="media mb-2">
                    <form method="post" action="{{route('pro.update')}}">
                  </div>
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                  <div class="row">
                    <div class="col-12 col-sm-12">
                      <div class="form-group">
                        <div class="controls">
                          <label>Name</label>
                          <input type="text" class="form-control" name="name" placeholder="name" value="{{$user->name ?? '' }}" required data-validation-required-message="This name field is required">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="controls">
                          <label>E-mail</label>
                          <input type="email" class="form-control" placeholder="email" name="email" value="{{$user->email ?? '' }}" required data-validation-required-message="This email field is required">
                        </div>
                      </div>
                    </div>
                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                      <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">
                        Update</button>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="users-edit">
        <div class="">
          <div class="card-content">
            <div class="card-body">
              <div class="">
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                  <div class="media mb-2">
                    <form action="{{route('reset.password')}}" method="post">
                  </div>
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                  <div class="row">
                    <div class="col-12 col-sm-12">
                      <div class="form-group">
                        <div class="controls">
                          <label>Old Password</label>
                          <input type="password" class="form-control" name="oldpassword">
                          @error('oldpassword')
                          <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="controls">
                          <label>New Password</label>
                          <input type="password" class="form-control" name="newpassword">
                          @error('newpassword')
                          <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="controls">
                          <label>Confirm New Password</label>
                          <input type="password" class="form-control" name="confirm_password">
                          @error('confirm_password')
                          <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                      </div>
                    </div>
                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                      <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">
                        Update</button>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
@stop
@section('js')
@stop