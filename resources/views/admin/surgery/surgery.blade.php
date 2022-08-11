@extends('layouts.master')
@section('title', 'Surgery | Order Pad')
@section('page-heading', 'All Surgeries')
@section('css')
@stop
@section('content')

<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm open_modal" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;"> <i class="fa fa-plus"></i> New Surgery</a>
</div>
<div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Create New Surgery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('surgery.store') }}" method="POST" id="add_form">
                    @csrf
                    <div class="col-xs-12 col-sm-12 col-md-12 for_ajaxload row">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                    <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
                    <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
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
                <h5 class="modal-title" id="staticBackdropLabel">Update Surgery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body edit-order-append">
            </div>
            <div class="modal-footer">
                <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                    <button type="submit" class="btn btn-primary" id="btn-edit">Save</button>
                    <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover datatables">
        <thead>
            <tr>
                <th width="">#</th>
                <th width="">Surgery</th>
                <th>Notes</th>
                <th width="">Address</th>
                <th width="">Email</th>
                <th>Phone</th>
                <th>Fax</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surgeries as $surgery)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area">
                    <div class="row-title @if($surgery->rxes()->where('status', 1)->exists()) text-danger @elseif( $surgery->rxes()->where('date_needed_by',\Carbon\Carbon::today()->format('yyyy-m-d'))->exists()) text-warning @endif">{{ $surgery->name }}</div>
                    <div class="action-btn">
                        <a data-url="{{ route('surgery.edit',$surgery->id) }}" class="edit_button" href="javascript:;">Edit</a> |
                        <a href="{{ route('rx.collection') }}?surgery={{ $surgery->id }}">View RX</a> |
                        <button type="button" onclick="deleteAlert('{{ route('surgery.destroy',$surgery->id) }}')" title="Delete" class="text-danger">Delete</button>
                    </div>
                </td>
                <td class="surgery-notes">
                    @foreach($surgery->notes as $note)
                    <p><span class="font-weight-bold">{{ $loop->iteration }}:</span> {{ $note->notes }}</p>
                    @endforeach
                </td>
                <td>{{ $surgery->address ?? 'N / A' }}</td>
                <td>{{ $surgery->email ?? 'N / A' }}</td>
                <td>{{$surgery->phone ?? 'N / A'}}</td>
                <td>{{$surgery->fax ?? 'N / A'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
@section('js')
<script>
    $(".open_modal").on('click', function() {
        $.ajax({
            type: "GET",
            url: "{{route('load_surgery')}}/",
            success: function(response) {
                $(".for_ajaxload").html(response);
                $('#staticBackdrop1').modal('show');
            }
        });
    });
    $(document).on('click', '.close', function(e) {
        $('.for_ajaxload').empty();
        $(".edit-order-append").empty();
    });
    $(document).ready(function() {
        $("#add_form").validate({
            rules: {
                name: {
                    required: true
                },
                address: {
                    required: true,
                },
                email: {
                    required: true,
                },
                ordering_method: {
                    required: true,
                }
            },
        });
        $("#edit_form").validate({
            rules: {
                name: {
                    required: true
                },
                address: {
                    required: true,
                },
                email: {
                    required: true,
                },
                ordering_method: {
                    required: true,
                }
            },
        });
    });
    $('#btn-save').click(function() {
        if ($('#add_form').valid()) {
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
                $(".edit-order-append").html(response);
            }
        });
    });
    $(".addjob").click(function() {
        var $clone = $('table.tbll tr.cloneme:first').clone().find("input").val("").end();
        console.log($clone);

        $('table.tbll').append($clone);
    });
    $('.tbll').on('click', '.rmv', function() {
        $(this).closest('tr').remove();
    });
</script>
@stop