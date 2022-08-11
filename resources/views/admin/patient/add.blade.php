@extends('layouts.master')
@section('title', 'Order | Order Pad')
@section('page-heading', 'Save Patient')

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


<form action="{{ route('patient.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        {{--<div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <strong> Patient Name *</strong>
                <input type="text" name="patient" class="form-control" placeholder="Patient Name" required>
            </div>
        </div>--}}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Choose Patient Name *</strong>
                <select class="form-control" name="patient" id="patient" required="">
                    <option hidden selected="">Choose Patient</option>
                    @foreach($patients as $patient)
                    <option value="{{ $patient->patient }}">{{ $patient->patient }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>DOB *</strong>
                <input type="text" name="dob" placeholder="DOB" data-date-format='dd-mm-yyyy' class="form-control datepicker pl-2" required="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Ordering Method *</strong>
                <input type="text" name="ordering_method" class="form-control" placeholder="Enter Method" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Rx Span</strong>
                <select name="weeks" required="" class="form-control days">
                    <option selected="" value="">Select Days</option>
                    <option value="7">7 days</option>
                    <option value="14">14 days</option>
                    <option value="28">28 days</option>
                    <option value="56">56 days</option>
                    <option value="84">84 days</option>
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Medication To Be Ordered *</strong>
                <input type="text" name="medication" class="form-control" required="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Order Starting Date *</strong>
                <input name="order_date" data-date-format='dd-mm-yyyy' class="form-control datepicker pl-2 order_date" required="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-group">
                <strong>Medication Starting Date *</strong>
                <input type="text" name="medication_date" data-date-format='dd-mm-yyyy' class="form-control datepicker pl-2 medication_date" required="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Notes:</strong>
                <input type="text" name="notes" class="form-control" placeholder="Notes">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 for_ajaxload">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-right">
          <button type="submit" class="btn btn-primary">Save</button>
      </div>
      <input type="hidden" min="0" name="order_no" value="0" class="form-control" required="">
      <input type="hidden" name="next_order_date" class="form-control">
      <input type="hidden" name="next_medication_date" class="form-control">
  </div>
</form>
@stop

{{-- page JS Script Section --}}
@section('js')
<script type="text/javascript">
    function addZero(val){
        return val < 10 ? ('0'+val) : val;
    }

    function addDays(d, days){
        days=parseInt(days);
        // d=parseInt(d[1])+'-'+parseInt(d[0])+'-'+parseInt(d[2]);
        // console.log(d);
        var date = new Date(d);
        date.setDate(date.getDate() + days);
        return addZero(date.getDate())+"-"+addZero(date.getMonth()+1)+"-"+date.getFullYear();
    }

    $('.days, .datepicker').on('change',function(){
        v1 = $('.order_date').val();
        v2 = $('.medication_date').val();
        v3 = $('.days').val();
        if(v1 !== "" && v2 !== ""&& v3 !== ""){
            $('[name="next_order_date"]').attr('value', addDays(v1.split('-'), v3));
            $('[name="next_medication_date"]').attr('value', addDays(v2.split('-'), v3));

        }
    });
    $(document).on('click', '.addjob', function() {
        var $clone = $('table.tbll tr.cloneme:first').clone().find("input:not(.med-st)").val("").end();
        $('table.tbll').append($clone);
    });
    $(document).on('click', '.rmv', function() {
        $(this).closest('tr').remove();
    });
    $(document).on('change', '[name="is_checked[]"]', function() {
        if ($(this).is(':checked'))
            $(this).next('input').val("1")
        else
            $(this).next('input').val("0")
    });
    $(document).on('change', '.check-all-meds', function() {
        if ($(this).is(':checked'))
            $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', true).change();
        else
            $(this).closest('table').find('tbody').find('[type="checkbox"]').prop('checked', false).change();
    });
    $(document).ready(function(){
        $("#patient").change(function() {
            var visitor = $("#patient option:selected").val();
            $.ajax({
                type: "GET",
                url: "{{route('load_patient')}}/" + visitor,
                success: function(response) {
                    $(".for_ajaxload").html(response);
                    console.log(response);
                }
            });
        });
    });
</script>
@stop