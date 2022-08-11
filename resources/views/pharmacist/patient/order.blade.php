@extends('layouts.pharmacist')
@section('title', 'Order | Order Pad')
@section('page-heading', "Today's Orders")
@section('css')
@stop
@section('content')
<div>
    <div class="text-right mb-3">
        <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;"> <i class="fa fa-plus"></i> Create New Order</a>
    </div>
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create New Order</h5>
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pharmacist.patient.store') }}" id="add_form" method="POST" enctype="multipart/form-data" id="first_form">
                        <div class="col-xs-12 col-sm-12 col-md-12" id="choose_patient">
                            <div class="form-group">
                                <strong>Choose Patient Name *</strong>
                                <select class="select2 form-control" name="rx_id" id="patient" required="">
                                    <option hidden selected="" value="choose">Choose Patient</option>
                                    @foreach($rxes as $rx)
                                    <option value="{{ $rx->id }}">{{ $rx->name ?? ""}} ({{ $rx->dob ?? ""}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 for_ajaxload row">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-order" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Update Order</h5>
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body edit-order-append">
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button type="submit" class="btn btn-primary" id="btn-edit">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-3 datatables">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>DOB</th>
                    <th>Rx span </th>
                    <th>No of Meds</th>
                    <th>Order Date</th>
                    <th>Notes</th>
                    <th>Medication Date</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                @if($order->pendingOrder !=="true")
                <tr>
                    <th>{{ $loop->iteration }}</th>
                    <td class="action-btn-area">
                        <div class="row-title">{{ $order->patient }}</div>
                        <div class="action-btn">
                            @if($order->previous_order_date !== $date)
                            <a href="{{ route('pharmacist.rx.create') }}?value={{$order->patient}}&rx_id={{$order->rx_id}}&order=1&today=1&approve=true&patient_id={{$order->id}}" class="text-info">Order</a> |
                            @else
                            <a href="{{ route('pharmacist.rx.create') }}?value={{$order->patient}}&rx_id={{$order->rx_id}}&order=1&today=1&approve=false" class="text-info">Order</a> |
                            @endif
                            <a data-url="{{route('pharmacist.patient.edit',$order->id)}}" class="edit_button" href="javascript:;">Edit</a> |
                            <button type="button" onclick="deleteAlert('{{ route('pharmacist.patient.destroy',$order->id) }}')" title="Delete" class="text-danger">Remove</button>
                        </div>
                    </td>
                    <td>{{$order->rx->dob ?? "N/A"}}</td>
                    <td>{{ $order->weeks ?? "N/A"}}</td>
                    <td>
                        @if( empty($order->allMeds()) && empty($order->orderedMeds()) )
                        N / A
                        @else
                        {{ $order->allMeds()." / ".$order->orderedMeds() ?? ""}}
                        @endif
                    </td>

                    <td>{{ date('d-m-Y', strtotime($order->order_date)) ?? "N/A"}}</td>
                    <td>{{ $order->notes ?? "N/A"}}</td>

                    <td>{{ $order->next_medication_date!=='' ? date('d-m-Y', strtotime($order->medication_date)) :  date('d-m-Y', strtotime($order->next_medication_date)) }}</td>

                    <td>{{ $order->previous_order_date == $date ? "" : "To Be Ordered"}}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
@section('js')
<script type="text/javascript">
    $flag = false;
    $(document).ready(function() {
        $("#patient").change(function() {
            var visitor = $("#patient option:selected").val();
            $(".edit-order-append").html('');
            $.ajax({
                type: "GET",
                url: "{{route('pharmacist.load_patient')}}/" + visitor,
                success: function(response) {
                    $(".for_ajaxload").html(response);
                    $('.datepicker').datepicker();
                    if (response.error) {
                        $flag = false;
                    } else {
                        $flag = true;
                    }
                }
            });
        });
    });
    $(document).ready(function() {
        $("#add_form").validate({
            rules: {
                weeks: {
                    required: true,
                },
                order_date: {
                    required: true
                },
                medication_date: {
                    required: true,
                },
            },
        });
        $("#edit_form").validate({
            rules: {
                weeks: {
                    required: true,
                },
                order_date: {
                    required: true
                },
                medication_date: {
                    required: true,
                },
            },
        });
    });
    $(document).on('click', '#close', function(e) {
        $(this).removeData();
        $('.for_ajaxload').empty();
        $(".edit-order-append").empty();
    });
    $('#btn-save').click(function() {
        var visitor = $("#patient option:selected").val();
        if ($('#add_form').valid() && visitor !== 'choose' && $flag == true) {
            $('#add_form').submit();
        }
    });
    $('#btn-edit').click(function() {
        if ($('#edit_form').valid()) {
            $('#edit_form').submit();
        }

    });
    $(document).on('click', '.edit_button', function(e) {
        e.preventDefault();
        $('.for_ajaxload').html('');
        var test = $(this).data('url');
        $.ajax({
            type: "GET",
            url: test,
            beforeSend: function() {
                $("#edit-order").modal('show');
                $('.edit-order-append').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
            },
            success: function(response) {
                $('.edit-order-append').empty();
                $(".edit-order-append").html(response);
                $('.datepicker').datepicker();
            }
        });
    });
    selectFunc();
</script>
@stop