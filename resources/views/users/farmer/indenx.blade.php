@extends('users.farmer.layout.app')
@section('title','Farmer')
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
            Total Milk Sale <br>
            {{ $totalMilk }} (ltr.)
        </span>
    </div>
    <div class="col-md-2 section">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Total Purchase <br>
            Rs.{{ $purchase }}
        </span>
    </div>
    <div class="col-md-2 section">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Total Due <br>
            Rs.{{ $due }}
        </span>
    </div>
</div>
@endsection
@section('js')

@endsection
