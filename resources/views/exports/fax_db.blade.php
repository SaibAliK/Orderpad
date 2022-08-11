<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title></title>
  <style type="text/css">
    table,
    td,
    th {
      border: 1px solid black;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }
  </style>
</head>

<body>
  <div style="width: 80%;margin:auto;">
    <span>{{$current_date ?? ""}}</span>
    <div>
      <h2 style="text-align: center;">Prescription Order Request</h2>
    </div>
    @if($request['surgery_name'])
    <p>To : {{$request['surgery_name'] ?? ""}}</p>
    @else
    <p>To : {{$surgery->name ?? ''}}</p>
    @endif

    @if($request['fax']=="true")
    <p style="float: right;">{{$surgery->fax ?? ''}}</p>
    @elseif($request['phone']=="true")
    <p style="float: right;">{{$surgery->phone ?? ''}}</p>
    @else
    @endif
    <p>Please can i order a repeat prescription for the following patient</p>
    <div>
      <table style="border-collapse: collapse;">
        <thead>
        </thead>
        <tbody class="mt-4">
          <tr>
            <th scope="row">Name</th>
            <td>{{$request['name'] ?? ""}}</td>
          </tr>
          <tr>
            <th scope="row">DOB</th>
            <td>{{$request['dob'] ?? ""}}</td>
          </tr>
          <tr>
            <th scope="row"><strong>Medicine</strong></th>
            <td><strong>Qty</strong></td>
          </tr>
          @if($request['drug'])
          @foreach( $request['drug'] as $key => $med)
          <tr>
            <th scope="row">{{ $request['drug'][$key] ?? '' }}</th>
            <td>{{ $request['quantity'][$key] ?? '' }}</td>
          </tr>
          @endforeach
          @endif
        </tbody>
      </table>
    </div>
    <p>{{$request['comment'] ?? ''}}</p>
    <p>Kind regards</p>
    <p>Richard</p>
  </div>
</body>

</html>