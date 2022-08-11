<div style="width: 80%;margin:auto;">
  <div>
    <a href="{{route('export-pdf',$rx->id)}}" class="btn btn-sm btn-info" style="float: right;">+ Print</a>
    <h2 style="text-align: center;" class="p-2 mt-2">Prescription Order Request</h2>
  </div>
  <p>To : {{$rx->surgery->name ?? ""}}</p>
  <p>Please can I order a repeat prescription for the following patient</p>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
      </thead>
      <tbody class="mt-4">
        <tr>
          <th scope="row">Name</th>
          <td>{{$rx->name ?? ""}}</td>
        </tr>
        <tr>
          <th scope="row">DOB</th>
          <td>{{$rx->dob ?? ''}}</td>
        </tr>
        <tr>
          <th scope="row"><strong>Medicine</strong></th>
          <td><strong>Qty</strong></td>
        </tr>
        @foreach($rx->medications as $medication)
        @if($medication->order_status==1)
        <tr>
          <th scope="row">{{ $medication->drug_name ?? '' }}</th>
          <td>{{ $medication->quantity ?? '' }}</td>
        </tr>
        @endif
        @endforeach
      </tbody>
    </table>
  </div>
  <p>Looking Pharmacy to pick up prescription. Thanks</p>
  <p>KIND REGARDS</p>
  <p>Richard</p>
  <p>{{ $rx->user->name ?? ""}}</p>
</div>