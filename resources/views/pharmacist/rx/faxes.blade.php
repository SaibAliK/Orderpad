@extends('layouts.pharmacist')
@section('title', 'RX | Order Pad')
@section('page-heading', 'Fax List')
@section('css')
@stop
@section('content')
<div>
    <div class="table-responsive">
        <table class="table table-hover mb-3 datatables">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Surgery</th>
                    <th>Pharmacy</th>
                    <th>Fax Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rxes as $rx)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td class="action-btn-area action-btn-area-rx">
                        <div class="row-title row-heading-width">{{ $rx->name ?? ""}}</div>
                        <div class="action-btn">
                            <a class="edit-rx open_modal" data-url="{{ route('pharmacist.showFax',$rx->id) }}" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;">View</a>
                        </div>
                    </td>
                    <td>{{ $rx->dob ?? ""}}</td>
                    <td>{{ $rx->surgery->name ?? ""}}</td>
                    <td>{{ $rx->user->name ?? ""}}</td>
                    <td>{{$rx->surgery->fax ?? 'N / A'}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body" id="ajax_load">
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                        <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script type="text/javascript">
    $(".open_modal").on('click', function() {
        var Url = $(this).data('url');
        $.ajax({
            type: "GET",
            url: Url,
            beforeSend: function() {
                $('#ajax_load').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
                $('#staticBackdrop1').modal('show');
            },
            success: function(response) {
                $('#ajax_load').empty();
                $("#ajax_load").html(response);
            }
        });
    });
    $(document).on('click', '#close', function(e) {
        $('#ajax_load').empty();
    });
</script>
@endsection