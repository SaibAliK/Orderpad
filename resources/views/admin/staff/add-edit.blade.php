@extends('layouts.master')
@section('title', 'Staff | Order Pad')
@section('page-heading', empty($staff->id) ? "Save Staff" : 'Edit Staff' )
@section('css')
@stop
@section('content')
@if((isset($staff)))
<form action="{{ route('staff.update',$staff->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @else
    <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
        @endif

        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $staff->name ?? '' }}" class="form-control" placeholder="Name" required>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email Address:</strong>
                    <input type="text" name="email" value="{{ $staff->email ?? '' }}" class="form-control" placeholder="Email address" required>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            @if(!empty($staff->id))
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Role</strong>
                    <select class="form-control" name="role">
                        <option hidden selected="" value="">Choose Role</option>
                        <option value="pharmacist" {{$staff->role == "pharmacist" ? 'selected' : ''}}>Pharmacist</option>
                        <option value="pharmacist_staff" {{$staff->role == "pharmacist_staff" ? 'selected' : ''}}>Pharmacy Staff</option>
                        <option value="driver" {{$staff->role == "driver" ? 'selected' : ''}}>Driver</option>
                    </select>
                    @error('role')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @else
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Role</strong>
                    <select class="form-control" name="role">
                        <option hidden selected="" value="">Choose Role</option>
                        <option value="pharmacist">Pharmacist</option>
                        <option value="pharmacist_staff">Pharmacy Staff</option>
                        <option value="driver">Driver</option>
                    </select>
                    @error('role')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Password:</strong>
                    <input type="password" name="password" value="{{$make_string ?? ''}}" class="form-control" placeholder="Password">
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Repeat Password:</strong>
                    <input type="password" name="confirm_password" value="{{$make_string ?? ''}}" class="form-control" placeholder="Repeat Password">
                    @error('repeat-password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
            </div>
        </div>
    </form>
    @stop

    @section('js')
    <script>
        (function($) {
            $.fn.checkFileType = function(options) {
                var defaults = {
                    allowedExtensions: [],
                    success: function() {},
                    error: function() {}
                };
                options = $.extend(defaults, options);
                return this.each(function() {
                    $(this).on('change', function() {
                        var value = $(this).val(),
                        file = value.toLowerCase(),
                        extension = file.substring(file.lastIndexOf('.') + 1);
                        if ($.inArray(extension, options.allowedExtensions) == -1) {
                            options.error();
                            $(this).focus();
                        } else {
                            options.success();
                        }
                    });
                });
            };
        })(jQuery);

        $(function() {
            $('#image').checkFileType({
                allowedExtensions: ['jpg', 'jpeg', 'png'],
                success: function() {
                    $('#btn-save').prop("disabled", false);
                },
                error: function() {
                    toastr.error("Logo Image not correct.");
                    $('#btn-save').prop("disabled", true);
                }
            });
        });
    </script>
    @stop