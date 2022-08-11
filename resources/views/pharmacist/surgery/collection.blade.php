@extends('layouts.pharmacist')
@section('title', 'RX Collection Detail | Order Pad')

@section('page-heading', (!empty($surgery_name) ? $surgery_name : ''))

@section('css')
@stop
@section('content')
<div class="text-right mb-3">
    <a class="btn btn-primary btn-sm" href="{{ route('pharmacist.rx.create') }}"> <i class="fa fa-plus"></i> Create New RX</a>
</div>
<div class="table-responsive">
    <table class="table table-hover mb-3 datatables">
        <thead>
            <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>DOB</th>
                <th>Urgent</th>
                <th>Surgery</th>
                <th>Pharmacy</th>
                <th>Order By</th>
                <th>UnCollected Count</th>
                <th>Required At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rxes as $rx)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="action-btn-area">
                    <div class="row-title row-heading-width @if($rx->status == 1) text-danger @endif @if ( \Carbon\Carbon::today()->format('yy-m-d')  == $rx->date_needed_by) text-warning @endif">{{ $rx->name }}</div>
                    <div class="action-btn">
                        <a href="{{ route('pharmacist.rx.show',$rx->id) }}" class="view-rx">View</a> |
                        <button type="button" onclick="deleteAlert('{{ route('pharmacist.rx.destroy',$rx->id) }}')" title="Delete" class="text-danger">Remove</button>
                        <div class="display-block">
                            <button class="font-weight-bold partially-collect-btn" data-urls="{{ route('pharmacist.rx.medication.all.collect',['id' => $rx->id]) }}?order_id={{$rx->patients->id ?? ''}}" data-id="{{ $rx->id }}" data-order="{{$rx->patients->id ?? ''}}"> Collect </button>
                        </div>
                    </div>
                </td>
                <td>{{ $rx->dob }}</td>
                <td>
                    @if($rx->status == 1)
                    <input type="checkbox" name="urgent" data-urls="{{route('pharmacist.rx.changeUrgentRX',$rx->id)}}?value=urgent" value="urgent" data-toggle="toggle" data-size="xs" data-onstyle="success" {{ $rx->status == 1 ? 'checked' : '' }}>
                    @else
                    <input type="checkbox" name="urgent" data-urls="{{route('pharmacist.rx.changeUrgentRX',$rx->id)}}?value=notUrgent" value="notUrgent" data-toggle="toggle" data-size="xs" data-onstyle="success" {{ $rx->status == 1 ? 'checked' : '' }}>
                    @endif
                </td>
                <td>{{ $rx->surgery->name ?? "N / A"}}</td>
                <td>{{ $rx->user->name ?? "N / A"}}</td>
                <td>{{ $rx->order_by ?? "N / A"}}</td>

                <td>{{$rx->medications->where('status', '0')->count()}}</td>
                <td>{{ $rx->date_needed_by ?? "N / A"}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="modal" tabindex="-1" role="dialog" id="partially_collect_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Collect Rxes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pharmacist.rx.popup.medication.collection.update') }}" method="post">
                <div class="modal-body pt-0 pb-0">
                    @csrf
                    <div id="render-medication-data"></div>
                </div>
                <div class="modal-footer">
                    <a href="" id="collect_At" class="btn btn-info">Collect All</a>
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
    $(document).on('change', 'input[name="urgent"]', function() {
        var urls = $('input[name="urgent"]').data('urls');
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
        $('#collect_At').attr('href', collect_all_urls);
        $.ajax({
            type: "GET",
            url: "{{ route('pharmacist.rx.popup.medication.collection') }}?id=" + id + "&order_id=" + order_id,
            success: function(response) {
                if (response.error) {
                    toastr.error("Medication Collection is not exist");
                }
                if (response.html) {
                    $('#render-medication-data').html(response.html);
                    $('#partially_collect_modal').modal('show');
                }
            }
        });
    });
</script>
@stop