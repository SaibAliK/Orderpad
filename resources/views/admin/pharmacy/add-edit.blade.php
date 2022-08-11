@extends('layouts.master')
@section('title', 'Pharmacy | Order Pad')
@section('page-heading', 'Save Pharmacy')
@section('css')
@stop
{{-- Page Content Section --}}

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif



@if((isset($pharmacy)))
<form action="{{ route('pharmacy.update',$pharmacy->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @else
    <form action="{{ route('pharmacy.store') }}" method="POST" enctype="multipart/form-data">
        @endif

        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Pharmacy Name:</strong>
                    <input type="text" name="name" value="{{ $pharmacy->name ?? '' }}" class="form-control" placeholder="Name" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Email Address:</strong>
                    <input type="text" name="email" value="{{ $pharmacy->email ?? '' }}" class="form-control" placeholder="Email address" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Phone:</strong>
                    <input type="number" name="phone" value="{{ $pharmacy->pharmacyProfile->phone ?? '' }}" class="form-control" placeholder="0900 7860 1" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Address:</strong>
                    <input type="text" name="address" value="{{ $pharmacy->pharmacyProfile->address ?? '' }}" class="form-control" placeholder="Address" required="">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
            </div>
        </div>
    </form>
    @stop

    {{-- page JS Script Section --}}
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