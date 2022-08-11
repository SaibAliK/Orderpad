@extends('layouts.master')
@section('title', 'Order | Order Pad')
@section('page-heading', 'Future Orders List' . (!empty($pharmacy) ? ' ('.$pharmacy->name.')' : ''))
@section('content')
<div>
    <div class="text-right mb-3">
        <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;"><i class="fa fa-plus"></i> Create New Order</a>
    </div>
    <div>
        <div>
            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Create New Order</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('patient.store') }}" id="add_form" method="POST" enctype="multipart/form-data" id="first_form">
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
                                    <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--update order modal--}}
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
                                    <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
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
                            <th>Rx Span</th>
                            <th>No of Meds</th>
                            <th>Order Date</th>
                            <th>Medication Date</th>
                            <th>Notes</th>
                            <th>Order Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                        @if($patient->pendingOrder=="true" && !empty($patient->pending_status_date) && 
                        empty($patient->rx->collected_at))
                        
                        @elseif($patient->pendingOrder=="true" && !empty($patient->pending_status_date) && !empty($patient->rx->collected_at))
                        
                        @else

                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td class="action-btn-area">
                                <div class="row-title">{{ $patient->patient }}</div>
                                <div class="action-btn">
                                    <a href="{{ route('rx.create') }}?value={{$patient->patient}}&rx_id={{$patient->rx_id}}&order=1&today=1" class="text-info">Order</a> |
                                    <a data-url="{{route('patient.edit',$patient->id)}}" class="edit_button" href="javascript:;">Edit</a> |
                                    <button type="button" onclick="deleteAlert('{{ route('patient.destroy',$patient->id) }}')" title="Delete" class="text-danger">Remove</button>
                                </div>
                            </td>
                            <td>{{$patient->rx->dob ?? ""}}</td>
                            <td>{{ $patient->weeks ?? ''}}</td>
                            <td>
                                @if( empty($patient->allMeds()) && empty($patient->orderedMeds()) )
                                N / A
                                @else
                                {{ $patient->allMeds()." / ".$patient->orderedMeds() ?? ""}}
                                @endif
                            </td>
                            {{--<td>{{ $patient->ordering_method  ?? ''}}</td>--}}


                            {{--<td>{{ $patient->user->name ?? ""}}</td>
                            <td>{{ $patient->surgery->name ?? ""}}</td>--}}

                            <td>{{ $patient->next_order_date!=='' ? date('d-m-Y', strtotime($patient->order_date)) :  date('d-m-Y', strtotime($patient->next_order_date)) }}</td>

                            <td>{{ $patient->next_medication_date!=='' ? date('d-m-Y', strtotime($patient->medication_date)) :  date('d-m-Y', strtotime($patient->next_medication_date)) }}</td>
                            
                            <td>{{ $patient->notes ?? ""}}</td>
                            <td>
                                @if($patient->pendingOrder=="true" && !empty($patient->pending_status_date) && empty($patient->rx->collected_at))
                                <span class="badge badge-info">Pending</span>

                                @elseif($patient->pendingOrder=="true" && !empty($patient->pending_status_date) && !empty($patient->rx->collected_at))
                                <span class="badge badge-success">Comleted</span>

                                @else
                                <span class="badge badge-primary">Please Wait</span>
                                @endif
                            </td>
                        </tr>

                        @endif
                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @stop
    @section('js')
    <script type="text/javascript">
        $flag = false;
        $(document).on('change', '.check-all-meds', function() {
            if ($(this).is(':checked'))
                $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', true).change();
            else
                $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', false).change();
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
        $(document).on('change', '#patient', function() {
            var visitor = $("#patient option:selected").val();
            $(".edit-order-append").html('');
            $.ajax({
                type: "GET",
                url: "{{route('load_patient')}}/" + visitor,
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
        $(document).on('click', '#close', function(e) {
            $(this).removeData();
            $('.for_ajaxload').empty();
            $(".edit-order-append").empty();
        });
        $(document).on('click', '.edit_button', function(e) {
            e.preventDefault();
            $('.for_ajaxload').html('');
            var test = $(this).data('url');
            $("#edit-order").modal('show');
            $.ajax({
                type: "GET",
                url: test,
                success: function(response) {
                    $(".edit-order-append").html(response);
                    $('.datepicker').datepicker({
                        dateFormat: 'dd-mm-yyyy'
                    });
                }
            });
        });
        $(document).on('change', '[name="order_status[]"]', function() {
            if ($(this).is(':checked'))
                $(this).next('input').val("1")
            else
                $(this).next('input').val("0")
        });;
        selectFunc();
    </script>
    @stop