 <div style="width: 80%;margin:auto;">
   <div>
     <a href="{{route('export-pdf')}}?requests={{$request ?? ''}}&fax={{$request['fax']}}&phone={{$request['phone']}}" class="btn btn-sm btn-info" style="float: right;">+ Print</a>
     <h2 style="text-align: center;" class="p-2 mt-2">Prescription Order Request</h2>
   </div>
   <div class="row">
     @if($request['surgery_name'])
     <div class="col-md-6">
       <p>To : {{$request['surgery_name'] ?? ""}}</p>
     </div>
     @else
     <div class="col-md-6">
       <p>To : {{$surgery->name ?? ''}}</p>
     </div>
     @endif

     @if($request['fax']=="true")
     <div class="col-md-6">
       <p style="float: right;">{{$surgery->fax ?? ''}}</p>
     </div>
     @elseif($request['phone']=="true")
     <div class="col-md-6">
       <p style="float: right;">{{$surgery->phone ?? ''}}</p>
     </div>
     @else
     @endif
   </div>
   <p>Please can I order a repeat prescription for the following patient</p>

   <div class="table-responsive">
     <table class="table table-bordered">
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

         @if(isset($request['checked_hidden']))
         @foreach( $request['checked_hidden'] as $key => $med)
         <tr>
           <th scope="row">{{ $request['drug'][$key] ?? '' }}</th>
           <td>{{ $request['quantity'][$key] ?? '' }}</td>
         </tr>
         @endforeach
         @endif
       </tbody>
     </table>
   </div>
   <br>
   <p>{{$request['comment'] ?? ''}}</p>
   <p>Kind regards</p>
   <p>Richard</p>
 </div>