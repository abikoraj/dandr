@extends('users.distributor.layout.app')
@section('title','Distributor')
@section('head-title')
{{ Auth::user()->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-2 section">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Total Balance <br>
            Rs.{{ $due }}
        </span>
    </div>

    <div class="col-md-2 section">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Total Transaction <br>
            Rs.{{ $purchase }}
        </span>
    </div>

    <div class="col-md-2 section">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Total Payment <br>
            Rs.{{ $pay }}
        </span>
    </div>
</div>
@endsection
@section('js')

@endsection
