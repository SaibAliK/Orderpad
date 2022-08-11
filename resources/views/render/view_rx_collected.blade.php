<div class="modal" tabindex="-1" role="dialog" id="rx-detail-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0 pb-0">
        <div class="row">
          <div class="col-md-8">
            <h4>Name: {{$rx->patients->patient ?? 'N/A'}}</h4>
          </div>
          <div class="col-md-4">
            <h4>DOB: {{$rx->dob ?? 'N/A'}}</h4>
          </div>
          <div class="col-md-12 mt-3" style="line-height: 0.7">

            <p>Pharmacy: {{$rx->patients->user->name ?? 'N/A'}}</p>
            <p>Surgery: {{$rx->patients->surgery->name ?? 'N/A'}}</p>
            @if($rx->patients)
            <p>Scheduled Order: <span class="badge badge-info">Yes</span></p>
            @else
            <p>Scheduled Order: <span class="badge badge-danger">No</span></p>
            @endif
            <p>Rx span: {{$rx->patients->weeks ?? 'N/A'}}</p>
            <p>Order by: {{ $rx->order_by ?? "N/A"}}</p>

            @if(isset($rx->previous_order_date))
            <p>Order sent on: N/A</p>
            <p>Medication start date: N/A</p>
            @else
            <p>Order sent on: {{ date('d-m-Y',strtotime($rx->patients->pending_status_date ?? '')) ?? " N / A"}}</p>
            <p>Medication start date: {{date('d-m-Y', strtotime($rx->patients->previous_medication_date ?? 'N/A')) ?? ''}}</p>
            @endif
            <p>Notes: {{ $rx->patients->notes ?? "N/A"}}</p>

          </div>
        </div>
        <h4 class="mt-4">Order Detail</h4>
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-bordered table-hover font-weight-bold">
              <thead>
                <tr>
                  <th width="10%">#</th>
                  <th width="45%">Drug</th>
                  <th width="45%">Quantity</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rx->medications as $medication)
                <tr>
                  <th>{{ $loop->iteration }}</th>
                  <td>{{ $medication->drug_name }}</td>
                  <td>{{ $medication->quantity }}</td>
                  <td>
                    @if($medication->status)
                    <span class="badge bg-success text-white">Collected</span>
                    @else
                    <span class="badge bg-danger text-white">Not Collected</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>