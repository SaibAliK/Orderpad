@extends('layouts.master')
@section('title', 'RX | Order Pad')
@section('page-heading', empty($_GET['order']) ? (empty($rx) ? 'Create RX' : 'Edit RX') : 'Order Pad')

@section('content')
@if($_GET['order']=='1')
<div class="card p-3 card-rx-form mb-3  patient_drop">
    <h2 class="pb-2"> <i class="fas fa-user-circle"></i> Patients</h2>
    <div class="col-xs-12 col-sm-12 col-md-12" id="">
        <div class="form-group">
            <label>Choose Patient Name *</label>
            <select class="select2 form-control" name="" id="patient" required="">
                <option hidden selected="" value="choose">Choose Patient</option>
                @foreach($rxes as $rxs)
                <option value="{{ $rxs->id }}" data-name="{{$rxs->name}}" {{ $rxs->id == ($_GET['rx_id'] ?? "") ? 'selected' : '' }}>{{ $rxs->name ?? ""}} ({{ $rxs->dob ?? ""}})</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@else
@endif
<div id="render-data">
    @if((isset($rx)))
    <form action="{{ route('rx.update',$rx->id) }}" class="form" method="POST" id="edit_form">
        @method('PUT')
        @else
        <form action="{{ route('rxs.save.func') }}" class="form" method="POST" id="add_form">
            @endif
            @csrf
            <input type="hidden" name="orderpad" value="{{ $_GET['order'] ?? 'false' }}">
            <input type="hidden" name="today" value="{{ $_GET['today'] ?? '1' }}">

            <div class="card p-3 card-rx-form mb-3">
                <h2 class="pb-2"> <i class="fas fa-user-circle"></i>General Information</h2>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="">FULL NAME *</label>
                            <input type="text" name="name" id="name" value="" class="form-control" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="">DOB *</label>
                            <input name="dob" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="{{ $rx->dob ?? '' }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label for="">CHOOSE SURGERY *</label>
                            <select class="form-control select2" name="surgery_id" required="" id="surgery_id">
                                <option hidden selected="" value="{{ $rx->surgery_id ?? '' }}">Choose Surgery</option>
                                @foreach(surgeries() as $surgery)
                                <option value="{{ $surgery->id }}" data-ordering_method="{{$surgery->ordering_method ?? ''}}">{{ $surgery->name }} ({{ $surgery->email }})</option>
                                @endforeach
                            </select>
                            @error('surgery_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card p-3 card-rx-form mb-3">
                <h2 class="pb-2"> <i class="fas fa-book-medical"></i> Medications</h2>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 table-responsive">
                        <table class="table table-bordered tbll">
                            <thead>
                                <tr>
                                    <th width="45%"><strong>Drug</strong></th>
                                    <th width="45%"><strong>Quantity (e.g. 1mg once a day)</strong></th>
                                    @if(!empty($_GET['order']) && !empty($rx))
                                    <th width="10%">
                                        <strong>Action</strong>
                                        <button type="button" class="btn btn-sm mt-2">
                                            <input type="checkbox" name="check_all" class="check-all-meds" value="1">
                                        </button>
                                    </th>
                                    @else
                                    <th></th>
                                    @endif
                                </tr>
                            </thead>

                            <tr class="cloneme">
                                <td>
                                    <div class="form-group">
                                        <input list="browsers" class="form-control" placeholder="Choose Drug" name="drug[]" id="browser">
                                        <datalist id="browsers">
                                            @foreach($drugs as $drug)
                                            <option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>
                                            @endforeach
                                        </datalist>
                                        {{--<select class="select2 form-control" name="drug[]" id="">
                                            <option hidden selected="" value="">Choose Drug</option>
                                            @foreach($drugs as $drug)
                                            <option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>
                                            @endforeach
                                        </select>--}}
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" class="form-control" placeholder="Quantity">
                                </td>
                                @if(!empty($_GET['order']) && !empty($rx))
                                <td class="text-center">
                                    <a class='rmv btn btn-danger btn-sm text-white mt-2'>X</a>
                                    <button type="button" class="btn btn-sm mt-2">
                                        <input type="checkbox" name="is_checked[]" value="1" class="med-st">
                                        <input type="hidden" name="checked_hidden[]" value="0" class="med-st">
                                    </button>
                                </td>
                                @else
                                <td class="text-center">
                                    <a class='rmv btn btn-danger btn-sm text-white mt-2'>X</a>
                                </td>
                                @endif
                            </tr>
                        </table>
                        <a class="addjob btn btn-sm text-white mt-3 {{empty($_GET['order']) ? 'bg-primary' : 'bg-danger'}}">+ Med</a>
                    </div>
                </div>
            </div>
            <div class="card p-3 card-rx-form mb-3">
                <h2 class="pb-2"> <i class="fas fa-calendar-alt"></i> Order Details</h2>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
                        <div class="form-group">
                            <label for="">ORDER BY *</label>
                            <input type="text" name="order_by" value="{{ auth()->user()->name }}" class="form-control" placeholder="Name" required="">
                        </div>
                    </div>
                    @if($_GET['order']=='0')
                    @else
                    <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
                        <div class="form-group">
                           <label for="">ORDER DATE *</label>
                            <input name="order_date" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="">DATE PRESCRIPTION NEEDED BY * </label>
                            <input name="date_needed" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" autocomplete="off">
                        </div>
                    </div>
                    @endif
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="">CHOOSE PHARMACY *</label>
                            <select class="form-control" name="pharmacy_id">
                                <option hidden selected="" value="{{ $rx->pharmacy_id ?? ''}}">Choose Pharmacy</option>
                                @foreach(pharmacies() as $pharmacy)
                                <option value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                                @endforeach
                            </select>
                            @error('pharmacy_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if(!empty($_GET['order']))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Comment </label>
                            <input type="text" class="form-control" name="comment" placeholder="Comment">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Surgery Ordering Method*</label>
                            <select class="form-control" name="send_via" id="send_via" required="">
                                <option value="" selected="">Choose Ordering Method</option>
                                <option value="email">Email</option>
                                <option value="fax">Fax</option>
                                <option value="phone">Phone</option>
                                <option value="other">Other (please state)</option>
                            </select>
                            <input type="text" name="other_ordering_method" value="" class="form-control" placeholder="Enter Method" style="display: none;">
                            @error('ordering_method')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif

                    <div class="col-xs-12 col-sm-12 col-md-12 text-right">

                        <a class="fax btn open_modal_new d-none btn-info" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;">View Template</a>

                        <a class="email btn open_modal_new d-none btn-info" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;">View Email Template</a>

                        <button type="submit" id="btn-save" class="btn {{empty($_GET['order']) ? 'bg-primary' : 'bg-danger'}}" style="color:white">{{ empty($_GET['order']) ? 'Save' : 'Add Order' }}</button>
                    </div>
                </div>
            </form>
            <div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body" id="ajax_load">
                        </div>
                        <div class="modal-footer">
                            <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                                <button type="button" class="btn btn-secondary closing" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop

        @section('js')
        <script>

         $(document).ready(function(){
           $(document).on('click','.proceed',function() {
            proceed_for_patient();
        });
           function proceed_for_patient() {
            Swal.fire({
                title: 'Confirmation',
                text: "Have You sent the order request to the surgery ?",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Go back',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $("#submit").submit();
                } else
                window.location.reload(true);
            });
        }
    });

         $(document).on('click', '.addjob', function() {
            var $orderpads_meds = '<tr class="cloneme"><td><div class="form-group"><input list="browsers" class="form-control" placeholder="Choose Drug" name="drug[]" id="browser"><datalist id="browsers">@foreach($drugs as $drug)<option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>@endforeach</datalist></div></td><td><input type="number" name="quantity[]" class="form-control" placeholder="Quantity"></td>@if(!empty($_GET['order ']) && !empty($rx))<td class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a><button type="button" class="btn btn-sm mt-2"><input type="checkbox" name="is_checked[]" value="1" class="med-st"><input type="hidden" name="checked_hidden[]" value="0" class="med-st"></button></td>@else<td class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a></td>@endif</tr>';
            var abc = "{{$_GET['value'] ?? ''}}";

            var $render_orderpad = '<tr class="cloneme"><td><div class="form-group"><input list="browsers" class="form-control" placeholder="Choose Drug" name="drug[]" id="browser"><datalist id="browsers">@foreach($drugs as $drug)<option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>@endforeach</datalist></div></td><td><input type="number" name="quantity[]" class="form-control" placeholder="Quantity"></td><td class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a><button type="button" class="btn btn-sm mt-2"><input type="checkbox" name="checked_hidden[]" class="med-st" value="1"><input type="hidden" name="is_checked[]" class="med-st" value="0"></button></td></tr>';

            if (abc == "") {
                $('table.tbll').append($orderpads_meds);
                selectFunc();
            } else {
                $('table.tbll').append($render_orderpad);
                selectFunc();
            }

        //var $clone = $('table.tbll tr.cloneme:first').clone().find("input:not(.med-st)").val("").end();
        //$('table.tbll').append($clone_code);

    });
         $(document).on('click', '.rmv', function() {
            $(this).closest('tr').remove();
        });
         $(document).on('change', '[name="is_checked[]"]', function() {
            if ($(this).is(':checked'))
                $(this).next('input').val("1")
            else
                $(this).next('input').val("0")
        });
         $(document).on('change', '.check-all-meds', function() {
            if ($(this).is(':checked'))
                $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', true).change();
            else
                $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', false).change();
        });
    </script>
    <script>
        $(document).ready(function() {

            function proceed() {
                Swal.fire({
                    title: 'Confirmation',
                    text: "Have You sent the order request to the surgery ?",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Go back',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $('#add_form').submit();
                    } else {
                        window.location.reload(true);
                    }
                });
            }

            $("#add_form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    dob: {
                        required: true,
                    },
                    surgery_id: {
                        required: true
                    },
                    pharmacy_id: {
                        required: true,
                    },
                    send_via: {
                        required: true,
                    },
                    date_needed: {
                        required: true,
                    }
                },
            });
            $('#btn-save').click(function() {
                if ($('#add_form').valid()) {
                    if (empty($_GET['order']) || empty($rx))
                        $('#add_form').submit();
                    else
                        proceed();
                }
            });
        });
        $(document).on('change', '#patient', function() {
            var visitor = $("#patient option:selected").val();
            var name = $("#patient option:selected").data('name');
            $('input[name="name"]').val(name);
            $.ajax({
                type: "GET",
                url: "{{ route('load.rx.patient') }}?value=" + visitor + "&today={{$_GET['today'] ?? ''}}&order={{ $_GET['order'] ?? ''}}&approve={{ $_GET['approve'] ?? ''}}&patient_id={{ $_GET['patient_id'] ?? ''}}",
                success: function(response) {
                    if (response.error) {} else {
                        $('#render-data').html(response.html);
                        initDatepicker(true);
                        selectFunc();
                    }
                }
            });
        });

        $(document).on('change', '#send_via', function() {
            var test = $('#send_via option:selected').val();
            if (test == "email") {
                $('.email').removeClass("d-none");
                $('.fax').addClass('d-none');
                $('input[name="other_ordering_method"]').hide();
            } else if (test == "fax") {
                $('.fax').removeClass('d-none');
                $('.email').addClass('d-none');
                $('input[name="other_ordering_method"]').hide();
            } else if (test == "phone") {
                $('.fax').removeClass('d-none');
                $('.email').addClass('d-none');
                $('input[name="other_ordering_method"]').hide();
            } else if (test == "other") {
                $('.fax').removeClass('d-none');
                $('.email').addClass('d-none');
                $('input[name="other_ordering_method"]').show();
            } else {
                $('input[name="other_ordering_method"]').hide();
            }
        });
        $(".open_modal_new").click(function() {
            var test = $('#send_via option:selected').val();
            if (test == "email") {
                $('input[name="other_ordering_method"]').hide();
                var MOVE = "{{route('rx.loadOrder_new')}}?email=true"
            } else if (test == "fax") {
                $('input[name="other_ordering_method"]').hide();
                var MOVE = "{{route('rx.loadOrder_new')}}?fax=true"
            } else if (test == "phone") {
                $('input[name="other_ordering_method"]').hide();
                var MOVE = "{{route('rx.loadOrder_new')}}?phone=true"
            } else if (test == "other") {
                $('input[name="other_ordering_method"]').show();
                var MOVE = "{{route('rx.loadOrder_new')}}?other=true"
            } else {
                $('input[name="other_ordering_method"]').hide();
            }
            $.ajax({
                type: "POST",
                url: MOVE,
                data: $('.form').serialize(),
                beforeSend: function() {
                    $('#ajax_load').empty();
                    $('#staticBackdrop1').modal('show');
                    $('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                },
                success: function(msg) {
                    console.log(msg);
                    $('#ajax_load').empty();
                    $('#ajax_load').append(msg);
                }
            });
        });

        $(document).on('change', '#send_via_render', function() {
            var test = $('#send_via_render option:selected').val();
            //$('#send_via_render option:selected').val('');
            if (test == "email") {
                $('.email').removeClass("d-none");
                $('.fax').addClass('d-none');
            } else if (test == "fax") {
                $('.fax').removeClass('d-none');
                $('.email').addClass('d-none');
            } else if (test == "phone") {
                $('.fax').removeClass('d-none');
                $('.email').addClass('d-none');
            } else if (test == "other") {
                $('.fax').removeClass('d-none');
                $('.email').addClass('d-none');
            } else {
                $('input[name="other_ordering_method"]').hide();
            }
        });

        $(document).on('click',".open_modal",function() {
                //$('#ajax_load').empty();
                var test = $('#send_via_render option:selected').val();
                if (test == "email") {
                    var MOVE = "{{route('rx.loadOrder')}}?email=true"
                } else if (test == "fax") {
                    var MOVE = "{{route('rx.loadOrder')}}?fax=true"
                } else if (test == "phone") {
                    var MOVE = "{{route('rx.loadOrder')}}?phone=true"
                } else if (test == "other") {
                    var MOVE = "{{route('rx.loadOrder')}}?other=true"
                } else {
                    $('input[name="other_ordering_method"]').hide();
                }
                $.ajax({
                    type: "POST",
                    url: MOVE,
                    data: $('.form').serialize(),
                    beforeSend: function() {
                        $('#ajax_load').empty();
                        $('#staticBackdrop1').modal('show');
                        $('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                    },
                    success: function(msg) {
                        $('#ajax_load').empty();
                        $('#ajax_load').append(msg);
                    }
                });
            });

        $(document).on('change', '#surgery_id', function() {
            var method = $("#surgery_id option:selected").data('ordering_method');
            $("#send_via").val(method);
            $("#send_via").trigger("change");
            $("#send_via_render").val(method);
            $("#send_via_render").trigger("change");
        });

        @if(isset($rx))
        @endif
        @if(isset($_GET['rx_id']))
        $('#patient').change();
        @endif
        selectFunc();
    </script>
    @endsection