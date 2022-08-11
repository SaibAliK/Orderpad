@extends('layouts.master')
@section('title', 'Pharmacy | Order Pad')
@section('page-heading', 'Pharmacies')
@section('css')
@stop
@section('content')

<div class="text-right mb-3">
  <a class="btn btn-primary btn-sm open_modal" data-toggle="modal" data-target="#staticBackdrop" href="javascript:;"> <i class="fa fa-plus"></i> New Pharmacy</a>
</div>
<div class="table-responsive">
  <div>
    <div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Create New Pharmacy</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{ route('pharmacy.store') }}" id="add_form" method="POST" enctype="multipart/form-data" id="first_form">
              @csrf
              <div class="col-xs-12 col-sm-12 col-md-12 for_ajaxload row">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div class="col-xs-12 col-sm-12 col-md-12 text-right">
              <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
          </div>
        </div>
      </div>
    </div>
    {{--update order modal--}}
    <div class="modal fade" id="edit-order" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Update Pharmacy</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body edit-order-append">
          </div>
          <div class="modal-footer">
            <div class="col-xs-12 col-sm-12 col-md-12 text-right">
              <button type="submit" class="btn btn-primary" id="btn-edit">Save</button>
              <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-3 datatables">
        <thead>
          <tr>
            <th>#</th>
            <th>Pharmacy Name</th>
            <th>Email</th>
            <th>Address</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pharmacists as $pharmacist)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td class="action-btn-area-pharmacy">
              <div class="row-title">{{ $pharmacist->name }}</div>
              <div class="action-btn">
                <a data-url="{{ route('pharmacy.edit',$pharmacist->id) }}" class="edit_button" href="javascript:;">Edit</a> |
                {{--<a href="{{ route('rx.index').'?pharmacy='.$pharmacist->id }}">RXs</a> |
                <a href="{{ route('patient.index').'?pharmacy='.$pharmacist->id }}">Orders</a> |--}}
                <button type="button" onclick="deleteAlert('{{ route('pharmacy.destroy',$pharmacist->id) }}')" title="Delete" class="text-danger">Delete</button>
              </div>
            </td>
            <td>{{ $pharmacist->email }}</td>
            <td>{{ $pharmacist->pharmacyProfile->address ?? 'N / A' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop

@section('js')
<script type="text/javascript">
  $(".open_modal").on('click', function() {
    $.ajax({
      type: "GET",
      url: "{{route('load_pharmacy')}}/",
      success: function(response) {
        $(".for_ajaxload").html(response);
        $('#staticBackdrop1').modal('show');
      }
    });
  });
  $(document).on('click', '.close , #close', function(e) {
    $('.for_ajaxload').empty();
    $(".edit-order-append").empty();
  });
  $(document).ready(function() {
    $("#add_form").validate({
      rules: {
        name: {
          required: true
        },
        email: {
          required: true,
          email: true
        },
        address: {
          required: true,
        },
      },
    });
    $("#edit_form").validate({
      rules: {
        name: {
          required: true
        },
        email: {
          required: true,
          email: true
        },
        address: {
          required: true,
        },
      },
    });
  });
  $('#btn-save').click(function() {
    if ($('#add_form').valid()) {
      $('#add_form').submit();
    }
  });
  $('#btn-edit').click(function() {
    if ($('#edit_form').valid()) {
      $('#edit_form').submit();
    }
  });
  $(document).on('click', '.edit_button', function(e) {
    e.preventDefault();
    $('.for_ajaxload').html('');
    var test = $(this).data('url');

    $.ajax({
      type: "GET",
      url: test,
      beforeSend: function() {
        $("#edit-order").modal('show');
        $('.edit-order-append').append("<i class='fas fa-spinner fa-spin'></i> &nbsp; Processing...");
      },
      success: function(response) {
        $(".edit-order-append").empty();
        $(".edit-order-append").html(response);
      }
    });
  });
</script>
@stop