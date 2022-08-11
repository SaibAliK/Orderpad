@csrf
<div class="row">
    <input type="" name="patient" value="{{$rx->name}}" hidden="">
    <input type="hidden" name="rx_id" value="{{$rx->id}}">
    <input type="hidden" name="pharmacy_id" value="{{ auth()->user()->parent_id }}">
    <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
        <div class="form-group">
            <strong>CHOOSE SURGERY * </strong>
            <select class="form-control" name="surgery_id" required="">
                <option hidden selected="" value="{{ $rx->surgery_id ?? '' }}">{{ $rx->surgery->name ?? 'Choose Surgery' }}</option>
                @foreach(surgeries() as $surgery)
                <option value="{{ $surgery->id }}">{{ $surgery->name }} ({{ $surgery->email }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
        <div class="form-group">
            <strong>DOB *</strong>
            <input type="text" name="dob" placeholder="DOB" data-date-format='dd-mm-yyyy' value="{{$rx->dob ?? ''}}" class="form-control datepicker pl-2" required="" readonly="" autocomplete="off">
        </div>
    </div>
    {{--<div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Ordering Method *</strong>
            <select class="form-control" name="ordering_method">
                <option hidden selected="" value="">Choose Method</option>
                <option value="email">Email</option>
                <option value="fax">Fax</option>
                <option value="phone">Phone</option>
                <option value="other">Other (please state)</option>
            </select>
            <input type="text" name="other_ordering_method" value="" class="form-control" placeholder="Enter State" style="display: none;">
            @error('ordering_method')
            <div class="alert alert-danger">{{ $message }}
</div>
@enderror
</div>
</div>--}}
<div class="col-xs-12 col-sm-12 col-md-6">
    <div class="form-group">
        <strong>Rx Span *</strong>
        <select name="weeks" required="" class="form-control days">
            <option selected="" value="">Select Days</option>
            <option value="7">7 days</option>
            <option value="14">14 days</option>
            <option value="28">28 days</option>
            <option value="56">56 days</option>
            <option value="84">84 days</option>
        </select>
        @error('weeks')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-6">
    <div class="form-group">
        <strong>Order Starting Date *</strong>
        <input name="order_date" data-date-format='dd-mm-yyyy' value="" class="form-control datepicker pl-2 order_date" required="" autocomplete="off">
        @error('order_date')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <strong>Medication Starting Date *</strong>
        <input type="text" name="medication_date" data-date-format='dd-mm-yyyy' value="" class="form-control datepicker pl-2 medication_date" required="" autocomplete="off">
        @error('medication_date')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="col-xs-12 col-sm-6 col-md-12">
    <div class="form-group">
        <strong>Notes:</strong>
        <input type="text" name="notes" class="form-control" value="" placeholder="Notes">
    </div>
</div>
<div class="col-xs-12 col-sm-12">
    <div class="card p-3 card-rx-form mb-3">
        <h2 class="pb-2"> <i class="fas fa-book-medical"></i> Medications</h2>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 table-responsive">
                <table class="table table-bordered tbll">
                    <thead>
                        <tr>
                            <th width="45%"><strong>Drug</strong></th>
                            <th width="45%"><strong>Quantity (e.g. 1mg once a day)</strong></th>
                            <th width="10%">
                                <strong>Action</strong>
                                <button type="button" class="btn btn-sm mt-2">
                                    <input type="checkbox" name="check_all" class="check-all-meds" value="1">
                                </button>
                            </th>
                        </tr>
                    </thead>
                    @foreach($rx->medications as $key => $medi)
                    <tr class="cloneme">
                        <td>
                            <input type="text" class="form-control" value="{{$medi->drug_name}}" name="drug[]" placeholder="Drug" required="" readonly="">
                        </td>
                        <td>
                            <input type="number" name="quantity[]" value="{{$medi->quantity}}" class="form-control" placeholder="Quantity" required="" readonly="">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm mt-2">
                                <input type="checkbox" name="order_status[{{ $key }}]" class="med-st" value="1">
                                <input type="hidden" name="checked_hidden[{{ $key }}]" class="med-st" value="1">
                            </button>

                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" min="0" name="order_no" value="0" class="form-control" required="">
<input type="hidden" name="next_order_date" class="form-control">
<input type="hidden" name="next_medication_date" class="form-control">
</div>
<script type="text/javascript">
    function addZero(val) {
        return val < 10 ? ('0' + val) : val;
    }

    function addDays(d, days) {
        days = parseInt(days);
        d = parseInt(d[2]) + '-' + parseInt(d[1]) + '-' + parseInt(d[0]);
        var date = new Date(d);
        date.setDate(date.getDate() + days);
        return addZero(date.getDate()) + "-" + addZero(date.getMonth() + 1) + "-" + date.getFullYear();
    }
    /*$(document).on('change', 'select[name="ordering_method"]', function() {
        var test = $('select[name="ordering_method"] option:selected').val();
        if (test == "other") {
            $('input[name="other_ordering_method"]').show();
        } else {
            $('input[name="other_ordering_method"]').hide();
        }
    });*/
    $(document).on('change', '.check-all-meds', function() {
        if ($(this).is(':checked'))
            $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', true).change();
        else
            $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', false).change();
    });
    $('.days, .datepicker').on('change', function() {
        v1 = $('.order_date').val();
        v2 = $('.medication_date').val();
        v3 = $('.days').val();
        if (v1 !== "" && v2 !== "" && v3 !== "") {
            $('[name="next_order_date"]').attr('value', addDays(v1.split('-'), v3));
            $('[name="next_medication_date"]').attr('value', addDays(v2.split('-'), v3));
        }
    });
</script>