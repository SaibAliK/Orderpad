@extends('layouts.master')
@section('title', 'RX | Order Pad')
@section('page-heading', 'RX List' . (!empty($pharmacy) ? ' ('.$pharmacy->name.')' : ''))
@section('css')
@stop
@section('content')

<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('rx.create') }}"> <i class="fa fa-plus"></i> Create New RX</a>
</div>
<div class="table-responsive">
    <table class="table  mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>RX Title</th>
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
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td class="action-btn-area action-btn-area-rx">
                    <div class="row-title row-heading-width @if($rx->status == 1) text-danger @endif @if ( \Carbon\Carbon::today()->format('yyyy-m-d')  == $rx->date_needed_by) text-warning @endif">{{ $rx->name }}</div>
                    <div class="action-btn">
                        <form action="{{ route('rx.destroy',$rx->id) }}" method="POST">
                            <a href="{{ route('rx.show',$rx->id) }}" class="view-rx">View</a> |
                            @csrf
                            @method('DELETE')
                            <button class="text-red" type="submit" onclick="return confirm('Are you sure?')">Remove</button>
                        </form>
                        <div class="display-block">
                            <a class="font-weight-bold" href="{{ route('rx.medication.all.collect',['id' => $rx->id]) }}">All Collected</a> |
                            <button class="font-weight-bold partially-collect-btn" data-id="{{ $rx->id }}">Partially Collect</button>
                        </div>
                    </div>
                </td>
                <td>{{ $rx->dob }}</td>
                <td>{{ $rx->surgery->name }}</td>
                <td>{{ $rx->user->name }}</td>
                <td>{{ $rx->order_by }}</td>
                <td>{{ $rx->comment }}</td>
                <td>{{$rx->order_date}}</td>
                <td>{{ $rx->date_needed_by }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal" tabindex="-1" role="dialog" id="partially_collect_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Partially Collect Rxes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('rx.popup.medication.collection.update') }}" method="post">
                <div class="modal-body pt-0 pb-0">
                    @csrf
                    <div id="render-medication-data"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Collect</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $(".partially-collect-btn").on('click', function() {
        var id = $(this).attr('data-id')
        $.ajax({
            type: "GET",
            url: "{{ route('rx.popup.medication.collection') }}?id=" + id,
            success: function(response) {
                $('#render-medication-data').html(response.html);
                $('#partially_collect_modal').modal('show');
            }
        });
    });
</script>
@stop