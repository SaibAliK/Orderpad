@extends('layouts.pharmacist')
@section('title', 'RX Collection Detail | Order Pad')
@section('page-heading', 'Uncollected Rxs' . (!empty($surgery_name) ? ' ('.$surgery_name.')' : ''))
@section('css')
<style>
    p {
        line-height: 0.7 !important;
    }
</style>
@stop
@section('content')
<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('pharmacist.rx.create') }}?rxes_B={{'true'}}"> <i class="fa fa-plus"></i> Add RX</a>
</div>
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>DOB</th>
                <th>Surgery</th>
                <th>Scheduled</th>
                <th>UnCollected Count</th>
                <th>Required At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rxes as $rx)
            @if($rx->patients)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="action-btn-area">
                    <div class="row-title row-heading-width @if($rx->status == 1) text-danger @endif @if ( \Carbon\Carbon::today()->format('yy-m-d')  == $rx->date_needed_by) text-warning @endif">{{ $rx->name }}</div>

                    <div class="action-btn">
                        <div class="display-block">
                            <a href="{{ route('pharmacist.rx.show',$rx->id) }}" class="view-rx">View</a> |
                            @if($rx->patients)
                            <button class="font-weight-bold partially-collect-btn" data-urls="{{ route('pharmacist.rx.medication.all.collect',['id' => $rx->id]) }}?order_id={{$rx->patients->id ?? ''}}" data-id="{{ $rx->id ?? ''}}" data-name="{{$rx->name ?? ''}}" data-dob="{{$rx->dob ?? ''}}"  data-order="{{$rx->patients->id ?? ''}}" data-mds_str_date="{{ date('d-m-Y',strtotime($rx->patients->previous_medication_date)) ?? ''}}" data-span="{{$rx->patients->weeks ?? ''}}">Collect</button>
                            @else
                            @endif
                        </div>
                        {{--@if($rx->status == 1)
                            <input type="checkbox" class="urgent" name="urgent" value="{{$rx->id}}" data-status="urgent" data-toggle="toggle" data-size="xs" data-onstyle="success" {{ $rx->status == 1 ? 'checked' : '' }}> |
                            @else
                            <input type="checkbox" class="urgent" name="urgent" val="{{$rx->id}}" data-status="notUrgent" data-toggle="toggle" data-size="xs" data-onstyle="success" {{ $rx->status == 1 ? 'checked' : '' }}> |
                            @endif--}}
                            <button type="button" onclick="removeAlert('{{ route('pharmacist.rx.destroy',$rx->id) }}')" title="Delete" class="text-danger">Remove</button>
                        </div>
                    </td>
                    <td>{{ $rx->dob ?? 'N/A'}}</td>
                    <td>{{ $rx->surgery->name ?? "N/A"}}</td>
                    <td>
                        @if($rx->patients)
                        <span class="badge badge-info">Yes</span>
                        @else
                        <span class="badge badge-danger">No</span>
                        @endif
                    </td>
                    <td>{{$rx->medications->where('status', '0')->count() ?? ''}}</td>
                    <td>{{ $rx->date_needed_by ?? "N / A"}}</td>
                    <td>
                        @if($rx->date_needed_by)
                        @if($rx->date_needed_by <= $current_date) 
                        <span class="badge badge-primary">Overdue</span>
                        @elseif($rx->date_needed_by > $current_date)
                        <span class="badge badge-danger">Pending</span>
                        @endif
                        @else
                        <span class="badge badge-primary">N/A</span>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="modal" tabindex="-1" role="dialog" id="partially_collect_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Collect Rx's</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pharmacist.rx.popup.medication.collection.update') }}" method="post" class="form">
                    <div class="modal-body pt-0 pb-0">
                        @csrf
                        <div id="render-medication-data"></div>
                    </div>
                    <div class="modal-footer">
                        <a href="" id="collect_At" class="btn btn-info">All Collected</a>
                        <button type="submit" class="btn btn-primary">Collected</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Second Modal --}}
    <div class="modal" tabindex="-1" role="dialog" id="second_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title dob_data"></h5>
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>--}}
                </div>
                <form action="{{route('pharmacist.rx.new.popup')}}?popup=second" method="post" class="second_form">
                    <div class="modal-body pt-0 pb-0">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="rx_id" value=""  class="rx_id">
                            <input type="hidden" name="order_id" value="" class="order_id">
                            <input type="hidden" name="medications[]" value="" id="meds">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Enter Date On Collected RXS</strong>
                                    <input name="collected_date" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Medication Start Date</strong>
                                    <input name="meds_start_date" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <p>Choose most recent date if mulitple Rxs</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary close_modal" data-closeType="second">Go Back</button>
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--third Modal --}}
    <div class="modal" tabindex="-1" role="dialog" id="third_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title dob_data"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('pharmacist.rx.new.popup')}}?popup=third" method="post" class="third_form">
                    <div class="modal-body pt-0 pb-0">
                        @csrf
                        <div id="" class="row">
                            <input type="hidden" name="rx_id" value=""  class="rx_id">
                            <input type="hidden" name="order_id" value="" class="order_id">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Next Order Date</strong>
                                    <input name="next_order_date" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>Next Medication Start Date</strong>
                                    <input name="next_meds_start_date" data-date-format='dd-mm-yyyy' class="form-control datepickr pl-2" required="" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <p id="span"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary close_modal" type="button" data-closeType="third">Remove</button>
                        <button type="submit" class="btn btn-primary">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @stop

    @section('js')
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script type="text/javascript">
        
        $(document).on('click','.close_modal',function(){
            var close_type = $(this).attr('data-closeType');
            if(close_type=="second" || close_type == "third")
            {
                var MOVE = "{{ route('pharmacist.rx.popup.medication.collection.update') }}?close_type=" + close_type;
                $.ajax({
                    type: "POST",
                    url: MOVE,
                    data: $('.second_form').serialize(),
                    beforeSend: function() {
                    },
                    success: function(msg) {
                        $('#second_modal').modal('hide');
                        if(close_type=='third')
                        {
                            $('#third_modal').modal('hide');
                        }
                        window.location.reload();
                    },
                    error: function() {
                        console.log('second popup error');                        
                    }
                    });
            }
            $(this).closest('.modal').modal('hide');
        });

        function removeAlert(url) {
            Swal.fire({
                title: 'Remove without collecting?',
                text: "This action will remove the order!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            });
        }
    </script>
    <script>
        $(document).on('change', 'input[name="urgent"]', function() {
            var abc = $(this).val();
            if ($(this).data('status') == "urgent") {
                var ur = "{{route('pharmacist.rx.changeUrgentRX')}}?id=" + abc + "&value=urgent";
            } else {
                var ur = "{{route('pharmacist.rx.changeUrgentRX')}}?id=" + abc + "&value=notUrgent";
            }
            $.ajax({
                type: "GET",
                url: urls,
                success: function(response) {
                    toastr.success("RX updated successfully.");
                    console.log(response);
                    window.location.reload();
                }
            });
        });
        $(document).on('click', '.partially-collect-btn', function() {
            var id = $(this).attr('data-id');
            var order_id = $(this).attr('data-order');
            var collect_all_urls = $(this).attr('data-urls');

            var name = $(this).attr('data-name');
            var dob = $(this).attr('data-dob');
            var meds_start_date = $(this).attr('data-mds_str_date');
            var span = $(this).attr('data-span');
            $('#span').text("+"+ span + " days");
            $('.dob_data').text("Schedule next order for " + name + ", " + dob);
            $('input[name="meds_start_date"]').val(meds_start_date);
            $('.rx_id').val(id);
            $('.order_id').val(order_id);

            $('#collect_At').attr('href', collect_all_urls);
            $.ajax({
                type: "GET",
                url: "{{ route('pharmacist.rx.popup.medication.collection') }}?id=" + id + "&order_id=" + order_id,
                success: function(response) {
                    if (response.error) {
                        toastr.error("Unable to Process");
                    }
                    if (response.html) {
                        $('#render-medication-data').html(response.html);
                        $('#partially_collect_modal').modal('show');
                    }
                }
            });
        });
        $('.form').on('submit', function(e){
            e.preventDefault();
            var data = $(this).serialize();
            var MOVE = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: MOVE,
                data: $('.form').serialize(),
                beforeSend: function() {
                        //$('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                    },
                    success: function(msg) {
                        toastr.success("All medications of RX has been collected.");
                        $('#partially_collect_modal').modal('hide');
                        $('#second_modal').modal('show');
                        $("#meds").val(msg.medications);
                    },
                    error: function() {
                        //alert('error occur here');
                        window.location.reload();
                    }
                });
        });
        $('.second_form').on('submit', function(e){
            e.preventDefault();
            var MOVE = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: MOVE,
                data: $('.second_form').serialize(),
                beforeSend: function() {
                        //$('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                    },
                    success: function(msg) {
                        $('#second_modal').modal('hide');
                        $('#third_modal').modal('show');
                        console.log(msg);
                        var next_order = msg.next_order;
                        var next_meds = msg.next_medication;
                        $('input[name="next_order_date"]').val(next_order);
                        $('input[name="next_meds_start_date"]').val(next_meds);
                    },
                    error: function() {
                        //alert('error occur here');
                        console.log('second popup error');
                        window.location.reload();
                    }
                });
        });
        $('.third_form').on('submit', function(e){
            e.preventDefault();
            var MOVE = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: MOVE,
                data: $('.third_form').serialize(),
                beforeSend: function() {
                        //$('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                    },
                    success: function(msg) {
                        toastr.success("New Order is created successfully");
                        $('#third_modal').modal('hide');
                        window.location.reload();
                    },
                    error: function() {
                        //alert('error occur here');
                    }
                });
        });
    </script>
    @stop