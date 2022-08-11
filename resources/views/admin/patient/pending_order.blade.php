@extends('layouts.master')
@section('title', 'Order | Order Pad')
@section('page-heading', 'Pending Orders')
@section('css')
@stop
@section('content')
<div>
    <div class="table-responsive">
        <table class="table table-hover mb-3 datatables">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>DOB</th>
                    <th>Rx Span</th>
                    <th>No of Meds</th>
                    <th>Order Sent On</th>
                    <th>Medication Date</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                @if(!empty($order->pending_status_date) && empty($order->rx->collected_at) )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="action-btn-area">
                        <div class="row-title">{{ $order->patient }}</div>
                        <div class="action-btn">
                            <a class="text-success open_modal reorder_btn" data-patient_id="{{$order->id ?? ''}}" data-rx_id="{{$order->rx_id ?? ''}}" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;">Re Order Rx</a>

                            {{--<a href="{{ route('rx.create') }}?value={{$order->patient}}&rx_id={{$order->rx_id}}&order=1&today=1&approve=true&patient_id={{$order->id}}&overdue=true" class="text-success">Re Order Rx</a>--}} |
                            <a href="{{ route('rx.collection') }}">Go to Uncollected list</a>
                        </div>
                    </td>
                    <td>{{$order->rx->dob ?? ""}}</td>
                    <td>{{ $order->weeks }}</td>
                    <td>
                        @if( empty($order->allMeds()) && empty($order->orderedMeds()) )
                        N / A
                        @else
                        {{ $order->allMeds()." / ".$order->orderedMeds() ?? ""}}
                        @endif
                    </td>
                    <td>{{ date('d-m-Y',strtotime($order->pending_status_date)) ?? " N / A"}}</td>
                    <td>
                        {{date('d-m-Y', strtotime($order->previous_medication_date ?? '')) ?? ''}}
                    </td>
                    <td>
                        @if( $order->pendingOrder=="true" && $order->overdue=="no" ) <span class="badge badge-danger">Pending</span>
                        @elseif( $order->pendingOrder=="true" && $order->overdue=="yes" )
                        <span class="badge badge-primary">Overdue</span>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="staticBackdropLabel">Create Re Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('rx.reorder')}}" id="add_form" method="POST" enctype="multipart/form-data" id="first_form">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="rx_id" value="">
                            <input type="hidden" name="patient_id" value="">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>ORDER DATE * </strong>
                                    <input name="order_date" data-date-format='dd-mm-yyyy' value="" autocomplete="off" class="form-control datepicker pl-2">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <strong>DATE PRESCRIPTION NEEDED BY * </strong>
                                    <input name="date_needed" data-date-format='dd-mm-yyyy' value="" autocomplete="off" class="form-control datepicker pl-2">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
                            <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
                        </div>
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
        $('.reorder_btn').on('click', function() {
            var rx_id = $(this).data('rx_id');
            var patient_id = $(this).data('patient_id');
            $('input[name="rx_id"]').val(rx_id);
            $('input[name="patient_id"]').val(patient_id);
            $('#staticBackdrop1').modal('show');
        });
        $("#add_form").validate({
            rules: {
                rx_id: {
                    required: true
                },
            },
        });
        $('#btn-save').click(function() {
            if ($('#add_form').valid()) {
                $('#add_form').submit();
            }
        });
        $(document).on('click', '#close', function(e) {
            $(this).removeData();
            $('.for_ajaxload').empty();
        });
    });
</script>
@stop