@extends('layouts.master')
@section('title', 'Surgery | Order Pad')
@section('page-heading', 'Save Surgery')

@section('css')

@stop


{{-- Page Content Section --}}

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if((isset($surgery)))
<form action="{{ route('surgery.update',$surgery->id) }}" method="POST">
    @method('PUT')
    @else
    <form action="{{ route('surgery.store') }}" method="POST">
        @endif
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $surgery->name ?? '' }}" class="form-control" placeholder="Name" required="">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email Address:</strong>
                    <input type="text" name="email" value="{{ $surgery->email ?? '' }}" class="form-control" placeholder="Email address">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Phone Number:</strong>
                    <input type="text" name="phone" value="{{ $surgery->phone ?? '' }}" class="form-control" placeholder="Phone Number">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Fax Number:</strong>
                    <input type="text" name="fax" value="{{ $surgery->fax ?? '' }}" class="form-control" placeholder="Fax Number">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Surgery Address:</strong>
                    <input type="text" name="address" value="{{ $surgery->address ?? '' }}" class="form-control" placeholder="Address" required="">
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="pb-2"> <i class="fas fa-pen"></i> Notes</h2>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered tbll">
                          @if((isset($surgery)))
                          @foreach($surgery->notes as $note)
                          <tr class="cloneme">
                            <td width="92%"><input type="text" value="{{ $note->notes }}" class="form-control" name="notes[]" placeholder="Notes" required=""></td>
                            <td width="8%" class="text-center"><a class='rmv btn btn-danger btn-sm text-white mt-2'> X</a></td>
                        </tr>
                        @endforeach
                        @endif
                        <tr class="cloneme">
                            <td width="92%"><input type="text" class="form-control" name="notes[]" placeholder="Notes" required=""></td>
                            <td width="8%" class="text-center"><a class='rmv btn btn-danger btn-sm text-white mt-2'> X</a></td>
                        </tr>
                    </table>
                    <a class="addjob btn btn-success btn-sm text-white mt-3">+ Notes</a>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-right">
          <button type="submit" class="btn btn-primary">Save</button>
      </div>
  </div>
</form>
@stop



{{-- page JS Script Section --}}
@section('js')
<script>
 $(".addjob").click(function () {
    var $clone = $('table.tbll tr.cloneme:first').clone().find("input").val("").end();
    console.log($clone);
    //$clone.append("<td class='text-center'>   <a class='rmv btn btn-danger btn-sm text-white'> X</a></td>");
    $('table.tbll').append($clone);
});

 $('.tbll').on('click', '.rmv', function () {
   $(this).closest('tr').remove();
});
</script>
@stop
