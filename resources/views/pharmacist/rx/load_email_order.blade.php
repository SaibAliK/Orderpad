 <div style="width: 80%;margin:auto;">
   <div>
     <h2 style="text-align: center;" class="p-2 mt-2">Prescription Order Request</h2>
   </div>
   <div class="row">
     <div class="col-xs-12 col-sm-12 col-md-12">
       <div class="form-group">
         <div class="input-group mb-3">
           <input type="text" class="form-control form-control-sm link" placeholder="Email" value="{{$rx->surgery->email ?? ''}}" style="">
           <div class="input-group-append">
             <button type="button" class="btn btn-sm btn-primary copy-link">Copy</button>
           </div>
         </div>
       </div>
     </div>
     <div class="col-xs-12 col-sm-12 col-md-12">
       <div class="form-group">
         <div class="input-group mb-3">
           <input type="text" class="form-control form-control-sm link" placeholder="Subject" value="Prescription Order Request" style="">
           <div class="input-group-append">
             <button type="button" class="btn btn-sm btn-primary copy-link">Copy</button>
           </div>
         </div>
       </div>
     </div>
     <div class="col-xs-12 col-sm-12 col-md-12">
       <div class="form-group">
         <div class="input-group mb-3">
           <textarea name="second_box_description_french" id="answer2" rows="10" cols="" class="form-control form-control-sm link" value="">
Dear {{$rx->surgery->name ?? ""}}

Please can I order a repeat prescription for the following patient:

Patient name: {{$request['name'] ?? ''}}

DOB : {{$request['dob'] ?? ''}}

Medication
@if(isset($request['checked_hidden']))    
@foreach( $request['checked_hidden'] as $key => $med)
{{ $request['drug'][$key] ?? '' }} / Quantity : {{ $request['quantity'][$key] ?? '' }}
@endforeach
@endif 
{{$request['comment'] ?? ''}}

Kind regards

Richard

{{$rx->user->name ?? ''}}
          </textarea>
           <div class="input-group-append">
             <button type="button" class="btn btn-sm btn-primary copy-link">Copy</button>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
 <script type="text/javascript">
   $(document).on("click", ".copy-link", function(event) {
     var elm = $(this).closest('.input-group').find('.link');
     $(elm).select();
     document.execCommand("copy");
     $('.copy-link').text('Copy');
     $(this).text('Copied');
   });
 </script>