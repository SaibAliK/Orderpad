@extends('layouts.pharmacist')
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
                    <input type="text" name="name" value="{{ $surgery->name ?? '' }}" class="form-control" placeholder="Name">
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
    
@stop