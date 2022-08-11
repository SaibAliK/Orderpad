@extends('layouts.master')
@section('title', 'RX | Order Pad')
@section('page-heading', 'Edit Patient')

@section('content')
<div>
    <form action="{{ route('rxs.save.func') }}" id="" method="POST" class="form">
        @csrf
        <input type="hidden" name="orderpad" value="{{ $_GET['order'] ?? 'false' }}">
        <input type="hidden" name="today" value="{{ $_GET['today'] ?? '0' }}">
        <input type="hidden" name="approve" value="{{ $approve }}">
        <input type="hidden" name="patient_id" value="{{ $patient_id }}">
        <div class="card p-3 card-rx-form mb-3">
            <h2 class="pb-2"> <i class="fas fa-user-circle"></i> General Information</h2>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="">FULL NAME *</label>
                        <div class="input-group">
                            <input type="text" name="name" id="name" value="{{ $rx->name ?? '' }}" class="form-control" placeholder="Name" readonly="">
                            <input type="hidden" name="rx_id" value="{{ $rx->id }}">
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label for="">DOB *</label>
                        <input name="dob" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="{{ $rx->dob ?? '' }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="">CHOOSE SURGERY *</label>
                        <select class="form-control select2" name="surgery_id" required="">
                            <option hidden selected="" value="{{ $rx->surgery_id ?? '' }}">{{ $rx->surgery->name ?? 'Choose Surgery' }}</option>
                            @foreach(surgeries() as $surgery)
                            <option value="{{ $surgery->id }}">{{ $surgery->name }} ({{ $surgery->email }})</option>
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
                        @foreach($rx->medications as $key => $medication)
                        <tr>
                            <td>
                                {{--<input type="text" class="form-control" value="{{ $medication->drug_name ?? '' }}" name="drug[]" placeholder="Drug" required="">--}}
                                <div class="form-group">
                                    <input list="browsers" class="form-control" value="{{$medication->drug_name ?? ''}}" placeholder="Choose Drug" name="drug[]" id="browser">
                                    <datalist id="browsers">
                                        @foreach($drugs as $drug)
                                        <option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>
                                        @endforeach
                                    </datalist>
                                    {{--<select class="select2 form-control" name="drug[]" id="">
                                        <option hidden selected="" value="">Choose Drug</option>
                                        @foreach($drugs as $drug)
                                        <option value="{{ $drug->name }}" {{ $drug->name == $medication->drug_name ? 'selected' : '' }}>{{ $drug->name ?? ""}}</option>
                                        @endforeach
                                    </select>--}}
                                </div>
                            </td>
                            <td>
                                <input type="text" name="quantity[]" value="{{ $medication->quantity ?? '' }}" class="form-control" placeholder="Quantity" required="">
                            </td>
                            @if(!empty($_GET['order']) && !empty($rx))
                            <td class="text-center">
                                <a class='rmv btn btn-danger btn-sm text-white mt-2'> X</a>
                                <button type="button" class="btn btn-sm mt-2">
                                    <input type="checkbox" name="checked_hidden[]" class="med-st" value="1" @if($medication->order_status) checked @endif>
                                    <input type="hidden" name="is_checked[]" class="med-st" value="{{ $medication->is_checked ? 1 : 0 }}">
                                </button>
                            </td>
                            @else
                            <td class="text-center">
                                <a class='rmv btn btn-danger btn-sm text-white mt-2'> X</a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        <tr class="cloneme">
                            <td>
                                <div class="form-group">
                                    <input list="browsers" class="form-control" value="" placeholder="Choose Drug" name="drug[]" id="browser">
                                    <datalist id="browsers">
                                        @foreach($drugs as $drug)
                                        <option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>
                                        @endforeach
                                    </datalist>
                                    {{--<select class="select2 form-control" name="drug[]" id="">
                                        <option hidden selected="" value="choose">Choose Drug</option>
                                        @foreach($drugs as $drug)
                                        <option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>
                                        @endforeach
                                    </select>--}}
                                </div>
                            </td>
                            <td><input type="number" name="quantity[]" class="form-control" placeholder="Quantity"></td>
                            @if(!empty($_GET['order']) && !empty($rx))
                            <td class="text-center">
                                <a class='rmv btn btn-danger btn-sm text-white mt-2'>X</a>
                                <button type="button" class="btn btn-sm mt-2">
                                    <input type="checkbox" name="checked_hidden[]" class="med-st" value="1">
                                    <input type="hidden" name="is_checked[]" class="med-st" value="0">
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
                <div class="col-xs-12 col-sm-12 col-md-6" style="display:none">
                    <div class="form-group">
                        <label for="">DATE PRESCRIPTION NEEDED BY * </label>
                        <input name="date_needed" data-date-format='dd-mm-yyyy' value="{{ $rx->date_needed_by ?? ''}}" autocomplete="off" class="form-control datepickr pl-2">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="">CHOOSE PHARMACY *</label>
                        <select class="form-control" name="pharmacy_id">
                            <option hidden selected="" value="{{ $rx->pharmacy_id ?? ''}}">{{ $rx->user->name ?? 'Choose Pharmacy' }}</option>
                            @foreach(pharmacies() as $pharmacy)
                            <option value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                            @endforeach
                        </select>
                        @error('pharmacy_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                {{--@if(!empty($_GET['order']))--}}
                <div class="col-xs-12 col-sm-12 col-md-12" style="display:none">
                    <div class="form-group">
                        <label for="">Surgery Ordering Method*</label>
                        <select class="form-control" name="send_via">
                            <option hidden selected="" value="{{ $rx->surgery->ordering_method  ?? ''}}">
                                {{ $rx->surgery->ordering_method  ?? 'Choose Ordering Method'}} {{$rx->surgery->other_ordering_method ?? ""}}
                            </option>
                            <option value="email">Email</option>
                            <option value="fax">Fax</option>
                            <option value="phone">Phone</option>
                            <option value="other">Other (please state)</option>
                        </select>
                        <input type="text" name="other_ordering_method" value="{{$rx->surgery->other_ordering_method ?? '' }}" class="form-control" placeholder="Enter Method" style="display: none;">
                        @error('ordering_method')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                {{--@endif--}}
                <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                    <button type="submit" class="btn {{empty($_GET['order']) ? 'bg-primary' : 'bg-danger'}}" style="color:white">{{ empty($_GET['order']) ? 'Save' : 'Proceed' }}</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
            var selectMethod = $('select[name="send_via"] option:selected').val();
            if (selectMethod == "email") {
                $('.email').show();
                $('.fax').hide();
            } else {
                $('.email').hide();
            }
            $(".open_modal").click(function() {
                if (selectMethod == "email") {
                    var MOVE = "{{route('rx.loadOrder')}}?email=true"
                } else {
                    var MOVE = "{{route('rx.loadOrder')}}"
                }
                $.ajax({
                    type: "POST",
                    url: MOVE,
                    data: $('.form').serialize(),
                    beforeSend: function() {
                        $('#staticBackdrop1').modal('show');
                        $('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                    },
                    success: function(msg) {
                        $('#ajax_load').empty();
                        $('#ajax_load').append(msg);
                    }
                });
            });
            $(document).on('change', 'select[name="send_via"]', function() {
                var test = $('select[name="send_via"] option:selected').val();
                if (test == "other") {
                    $('input[name="other_ordering_method"]').show();
                } else {
                    $('input[name="other_ordering_method"]').hide();
                }
            });
            $(document).on('click', '.closing', function(e) {
                $('#ajax_load').empty();
            });
        });
        selectFunc();
    </script>
    @endsection