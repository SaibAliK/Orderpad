@extends('layouts.pharmacist')
@section('title', 'RX | Order Pad')
@section('page-heading', 'Add RX')
@section('content')
<div class="card p-3 card-rx-form mb-3">
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
<div id="render-data">
    <form action="{{route('pharmacist.create.uncollected.rxes')}}" method="POST" id="add_form">
        @csrf
        <input type="hidden" name="rxes_B" value="{{ $_GET['rxes_B'] ?? 'false' }}">

        <div class="card p-3 card-rx-form mb-3">
            <h2 class="pb-2"> <i class="fas fa-user-circle"></i>General Information</h2>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>FULL NAME *</label>
                        <input type="text" name="name" id="name" value="" class="form-control" placeholder="Name">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>DOB *</label>
                        <input name="dob" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>CHOOSE SURGERY *</label>
                        <select class="form-control select2" name="surgery_id">
                            <option hidden selected="" value="">Choose Surgery</option>
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

                        <tr class="cloneme">
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="unknown_drug[]" placeholder="UnKnown Med">
                                    {{--<select class="select2 form-control" name="drug[]" id="">
                                        <option hidden selected="" value="">UnKnown Med</option>
                                        @foreach($drugs as $drug)
                                        <option value="{{ $drug->name }}">{{ $drug->name ?? ""}}</option>
                                    @endforeach
                                    </select>--}}
                                </div>
                            </td>
                            <td>
                                <input type="text" name="unknown_quantity[]" class="form-control" placeholder="?">
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
                    <a class="addjob btn btn-sm text-white mt-3 bg-primary">+ Meds</a>
                    <a class="addjob_for_rxes btn btn-sm text-white mt-3 bg-info">+ Unknown Med</a>
                </div>
            </div>
        </div>
        <div class="card p-3 card-rx-form mb-3">
            <h2 class="pb-2"> <i class="fas fa-calendar-alt"></i> Order Details</h2>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
                    <div class="form-group">
                        <label>ORDER BY *</label>
                        <input type="text" name="order_by" value="{{ auth()->user()->name }}" class="form-control" placeholder="Name" required="">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6" style="">
                    <div class="form-group">
                        <label>ORDER DATE *</label>
                        <input name="order_date" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>DATE PRESCRIPTION NEEDED BY * </label>
                        <input name="date_needed" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>CHOOSE PHARMACY *</label>
                        <select class="form-control select2" name="pharmacy_id">
                            <option hidden selected="" value="">Choose Pharmacy</option>
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
                        <label>Surgery Ordering Method*</label>
                        <select class="form-control" name="send_via" required="">
                            <option value="email">Email</option>
                            <option value="fax" selected="">Fax</option>
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
                <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
                    <div class="form-group">
                        <label>COMMENT </label>
                        <input type="text" class="form-control" name="comment" placeholder="Comment">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                    <button type="submit" id="btn-save" class="btn {{empty($_GET['order']) ? 'bg-primary' : 'bg-danger'}}" style="color:white">{{ empty($_GET['order']) ? 'Save' : 'Add Order' }}</button>
                </div>
            </div>
    </form>
</div>
@stop

@section('js')
<script>
    $(document).on('click', '.addjob', function() {
        var $render_orderpad = '<tr class="cloneme"><td><div class="form-group"><select class="select2 form-control" name="drug[]" id=""><option hidden selected="" value="">Choose Drug</option>@foreach($drugs as $drug)<option value="{{ $drug->name}}">{{ $drug->name ?? ""}}</option>@endforeach</select></div></td><td><input type="text" name="quantity[]" class="form-control" placeholder="Quantity"></td><td class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a></td></tr>';

        $('table.tbll').append($render_orderpad);
    });

    $(document).on('click', '.addjob_for_rxes', function() {
        var $rx_meds = '<tr class="cloneme"><td><input type="text" class="form-control" name="drug[]" placeholder="UnKnown Med"></td><td><input type="text" name="quantity[]" class="form-control" placeholder="?"></td>@if(!empty($_GET['
        order ']) && !empty($rx))<td class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a><button type="button" class="btn btn-sm mt-2"><input type="checkbox" name="is_checked[]" value="1" class="med-st"><input type="hidden" name="checked_hidden[]" value="0" class="med-st"></button></td>@else<td class="text-center"><a class="rmv btn btn-danger btn-sm text-white mt-2">X</a></td>@endif</tr>';
        $('table.tbll').append($rx_meds);
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
                date_needed: {
                    required: true,
                }
            },
        });
        $('#btn-save').click(function() {
            if ($('#add_form').valid()) {
                $('#add_form').submit();
            }
        });
    });
    $(document).on('change', '#patient', function() {
        var visitor = $("#patient option:selected").val();
        var name = $("#patient option:selected").data('name');
        $('input[name="name"]').val(name);
        $.ajax({
            type: "GET",
            url: "{{ route('pharmacist.load.rx.patient') }}?value=" + visitor + "&today={{$_GET['today'] ?? ''}}&order={{ $_GET['order'] ?? ''}}&approve={{ $_GET['approve'] ?? ''}}&patient_id={{ $_GET['patient_id'] ?? ''}}",
            success: function(response) {
                if (response.error) {} else {
                    $('#render-data').html(response.html);
                    initDatepicker(true);
                    selectFunc();
                }
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
    $(document).on('click', '.btn-load-patient', function() {
        var self = $(this);
        var str = $("#name").val();
        if (str != "") {
            self.attr("disabled", true);
            self.text('Loading...');
            $.ajax({
                type: "GET",
                url: "{{ route('pharmacist.load.rx.patient') }}?value={{$_GET['rx_id'] ?? ''}}&today={{$_GET['today'] ?? ''}}&order={{ $_GET['order'] ?? '' }}&approve={{ $_GET['approve'] ?? '' }}&patient_id={{ $_GET['patient_id'] ?? '' }}&overdue={{$_GET['overdue'] ?? ''}}",
                success: function(response) {
                    if (response.error) {
                        self.text('Load Patient');
                        self.attr('disabled', false);
                    } else {
                        $('#render-data').html(response.html);
                        initDatepicker(true);
                    }
                }
            });
        }
    });
    @if(isset($rx))
    @endif
    @if(isset($_GET['rx_id']))
    $('#patient').change();
    @endif
    selectFunc();
</script>
@endsection