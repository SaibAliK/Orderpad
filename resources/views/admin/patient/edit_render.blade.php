<form action="{{ route('patient.update',$patient->id) }}" method="POST" id="edit_form" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Patient Name *</strong>
                <input type="text" name="patient" value="{{ $patient->patient ?? '' }}" class="form-control" placeholder="Patient Name" required>
                @error('patient')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>CHOOSE PHARMACY *</strong>
                <select class="form-control" name="pharmacy_id">
                    <option hidden selected="" value="{{ $patient->pharmacy_id ?? ''}}">{{ $patient->user->name  ?? 'Choose Pharmacy'}}</option>
                    @foreach(pharmacies() as $pharmacy)
                    <option value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                    @endforeach
                </select>
                @error('pharmacy_id')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>CHOOSE SURGERY *</strong>
                <select class="form-control" name="surgery_id" required="">
                    <option hidden selected="" value="{{ $patient->surgery_id  ?? ''}}">{{ $patient->surgery->name  ?? 'Choose Surgery'}}</option>
                    @foreach(surgeries() as $surgery)
                    <option value="{{ $surgery->id }}">{{ $surgery->name }}</option>
                    @endforeach
                </select>
                @error('surgery_id')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        {{--<div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Ordering Method *</strong>
                <input type="text" name="ordering_method" value="{{ $patient->ordering_method  ?? ''}}" class="form-control" placeholder="Method" required>
                @error('ordering_method')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>--}}

        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Rx Span *</strong>
                <select name="weeks" required="" class="form-control weeks">
                    <option hidden selected="" value="{{ $patient->weeks  ?? ''}}">{{ $patient->weeks  ?? 'Select '}} days</option>
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
                <strong>Order Date</strong>
                <input name="order_date" data-date-format='dd-mm-yyyy' value="{{ date('d-m-Y', strtotime($patient->order_date)) }}" class="form-control datepicker pl-2" readonly="" required="">
                @error('order_date')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Medication Date</strong>
                <input type="text" data-date-format='dd-mm-yyyy' value="{{ date('d-m-Y', strtotime($patient->medication_date)) }}" name="medication_date" class="form-control datepicker pl-2" readonly="">
                @error('medication_date')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
            <div class="form-group">
                <strong>Following Order Date *</strong>
                <input type="text" id="date3" name="next_order_date" class="form-control" required="" readonly="" value="{{ date('d-m-Y', strtotime($patient->next_order_date)) }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6" style="display: none;">
            <div class="form-group">
                <strong>Following Medication Date *</strong>
                <input type="text" id="date4" name="next_medication_date" class="form-control" required="" readonly="" value="{{ date('d-m-Y', strtotime($patient->next_medication_date)) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class='col-xs-12 col-sm-12 col-md-6 orderGapDiv {{ ($patient->weeks!==7 && $patient->weeks!==14) ? "" : "hidden" }}'>
            <div class="form-group">
                <strong>Order Gap Management *</strong>
                <select name="order_gap_status" class="form-control s1">
                    <option {{ $patient->order_gap_status==0 ? "selected" : "" }} value="0">OFF</option>
                    <option {{ $patient->order_gap_status==1 ? "selected" : "" }} value="1">ON</option>
                </select>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 orderGap {{ ($patient->order_gap == "" || $patient->order_gap == "0") ? "hidden" : "" }}'>
            <div class="form-group">
                <strong>Preferred Order Gap *</strong>
                <select name="order_gap" class="form-control s2">
                    <option hidden selected="" value="{{ $patient->order_gap  ?? '' }}">{{ $patient->order_gap !== null ? $patient->order_gap." days" : 'Select Order Gap'}}</option>
                    <option value="4">4 days</option>
                    <option value="7">7 days</option>
                    <option value="10">10 days</option>
                    <option value="14">14 days</option>
                    <option value="21">21 days</option>
                </select>
            </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-6 orderGap {{ ($patient->order_gap == "" || $patient->order_gap == "0") ? "hidden" : "" }}'>
            <div class="form-group">
                <strong>Request Prescription Early Until Order Gap Achieved *</strong>
                <select name="order_gap_achieved" class="form-control s3">
                    <option hidden selected="" value="{{ $patient->order_gap_achieved  ?? null }}">{{ $patient->order_gap_achieved!==null ? $patient->order_gap_achieved." days"  : 'Select days'}}</option>
                    <option value="3" selected="">3 days</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <input type="hidden" name="rx_id" value="{{$rx->id}}">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="card p-3 card-rx-form mb-3">
                <h2 class="pb-2"> <i class="fas fa-book-medical"></i> Medications</h2>
                <div class="table-responsive">
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
                                    <input type="checkbox" name="order_status[{{ $key }}]" class="med-st" value="1" @if($medi->order_status) checked @endif>
                                    <input type="hidden" name="checked_hidden[{{ $key }}]" class="med-st" value="{{ $medi->order_status ? 1 : 0 }}">
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    function addZero(val) {
        return val < 10 ? ('0' + val) : val;
    }

    $('.s1').on('change', function() {
        if ($(this).val() == "1") {
            $('.orderGap').show();
        } else {
            $('.orderGap').hide();
            $('.s2').val("");
            $('.s3').val("");
        }
        medicationDate();
    });

    function addDays(d, days, f) {
        days = parseInt(days);
        if (f && $('.s3').val() !== "" && $('.s3').val() !== null)
            days = days - parseInt($('.s3').val());

        d = parseInt(d[2]) + '-' + parseInt(d[1]) + '-' + parseInt(d[0]);
        var date = new Date(d);

        date.setDate(date.getDate() + days);
        return addZero(date.getDate()) + "-" + addZero(date.getMonth() + 1) + "-" + date.getFullYear();
    }

    $('.s2, .s3, .datepicker').on('change', function() {
        medicationDate();
    });

    $('.weeks').on('change', function() {
        orderGap($(this).val());
    });

    function medicationDate() {
        var val = parseInt($('[name=weeks]').val());
        if (val !== "") {
            $('#date4').val(addDays($('[name=medication_date]').val().split('-'), val, false));
            $('#date3').val(addDays($('[name=order_date]').val().split('-'), val, true));
        }
    }

    function orderGap(val) {
        val = parseInt(val);
        if (val !== 7 && val !== 14) {
            $('.orderGapDiv').removeClass('hidden');
            $('.orderGap').hide();
            $('.s1').val(0);
            $('.s2').val("");
            $('.s3').val("");
        } else {
            $('.orderGapDiv').addClass('hidden');
            $('.orderGap').hide();
            $('.s1').val(0);
            $('.s2').val("");
            $('.s3').val("");
        }
        medicationDate();
    }
</script>