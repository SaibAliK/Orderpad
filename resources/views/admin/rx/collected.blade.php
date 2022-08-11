@extends('layouts.master')
@section('title', 'RX Collected List | Order Pad')
@section('page-heading', 'Collected Rxs')
@section('css')
<style type="text/css">
    table td,
    table td .row-title {
        color: #9B9B9B !important;
    }
</style>
@stop
@section('content')
<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('rx.create') }}?rxes_B={{'true'}}">
        <i class="fa fa-plus"></i> Add RX
    </a>
</div>
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Note</th>
                <th>DOB</th>
                <th>Surgery</th>
                <th>Pharmacy</th>
                <th>Order By</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Required At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rxes as $rx)
            @php
            $status_count = $rx->medications->where('status',1)->count();
            @endphp
            @if($status_count)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area">
                    <div class="row-title row-heading-width">{{ $rx->name }}</div>
                    <div class="action-btn">
                        <a href="{{ route('rx.show',$rx->id) }}" class="view-rx btn btn-sm btn-primary">View</a>
                        @if(empty($rx->note))
                        <a href="#" data-uri="{{route('save_collected_note')}}" type="button" data-rx_id="{{$rx->id ?? ''}}" class="note btn btn-sm btn-success"  id="note">Add Note</a>
                        @else
                        <a href="#" data-uri="{{route('save_collected_note')}}?task=update" data-note="{{$rx->note ?? ''}}" type="button" data-rx_id="{{$rx->id ?? ''}}" class="note btn btn-sm btn-info"  id="update_task">Update Note</a>
                        <a href="#" data-uri="{{route('save_collected_note')}}?task=delete" type="button" data-rx_id="{{$rx->id ?? ''}}" class="note btn btn-sm btn-danger"  id="delete_task">Delete Note</a>
                        @endif
                        <br>
                        {{--<strong>Note: </strong><span>{{$rx->note ?? 'N/A'}}</span>--}}  
                    </div>
                </td>
                <td>{{$rx->note ?? 'N/A'}}</td>
                <td>{{ $rx->dob }}</td>
                <td>{{ $rx->surgery->name ?? ""}}</td>
                <td>{{ $rx->user->name ?? ""}}</td>
                <td>{{ $rx->order_by ?? 'N/A'}}</td>
                <td>{{ $rx->comment ?? "N/A"}}</td>
                <td>{{$rx->order_date ?? ""}}</td>
                <td>{{ $rx->date_needed_by ?? ""}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    <p class="text-center text-danger">Entries will disappear after 10 days</p>
</div>

<div class="modal" tabindex="-1" role="dialog" id="note_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title "></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" class="note_form">
                <div class="modal-body pt-0 pb-0">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="rx_id" value=""  id="rx_id">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Enter Note</strong>
                                <textarea class="form-control" name="note" rows="3" cols="" id="note_box"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button class="btn btn-secondary close_modal" type="button" data-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $(document).ready(function(){
        $(document).on('click','#note',function(){
            var rx_id = $(this).attr('data-rx_id');
            var uri = $(this).attr('data-uri');
            $('.note_form').find("#rx_id").val(rx_id);
            $(".note_form").attr('action',uri);
            $("#note_modal").modal('show');
        });
        $('.note_form').on('submit', function(e){
            e.preventDefault();
            var data = $(this).serialize();
            var MOVE = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: MOVE,
                data: $('.note_form').serialize(),
                beforeSend: function() {
                },
                success: function(msg) {
                        //toastr.success("Notes added successfully");
                        $('#note_modal').modal('hide');
                        $('.note_form').find('input[name="note"]').empty();
                        window.location.reload();
                    },
                    error: function() {
                        console.log("error");
                    }
                });
        });
        $(document).on('click','#delete_task',function(){
            var rx_id = $(this).attr('data-rx_id');
            var uri = $(this).attr('data-uri');
            $('.note_form').find("#rx_id").val(rx_id);
            $(".note_form").attr('action',uri);
            $('.note_form').submit();
            window.location.reload();
        });

        $(document).on('click','#update_task',function(){
            var rx_id = $(this).attr('data-rx_id');
            var uri = $(this).attr('data-uri');
            var note = $(this).attr('data-note');
            $('.note_form').find('#note_box').val(note);
            $('.note_form').find("#rx_id").val(rx_id);
            $(".note_form").attr('action',uri);
            $("#note_modal").modal('show');
        });
    });
</script>
@stop