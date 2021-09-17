@extends('admin.layouts.app')
@section('title','Report')
@section('css')

@endsection
@section('head-title','Report')
@section('toobar')

@endsection
@section('content')
<div class="row">
    <div class="col-md-2 section href" data-target="{{route('admin.report.farmer')}}">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Farmer Report
        </span>
    </div>
    <div class="col-md-2 section href" data-target="{{route('admin.report.milk')}}">
        <span class="icon">
            <i class="zmdi zmdi-view-agenda"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Milk Report
        </span>
    </div>
    <div class="col-md-2 section href" data-target="{{route('admin.report.sales')}}">
        <span class="icon">
            <i class="zmdi zmdi-money-box"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Sales Report
        </span>
    </div>
    <div class="col-md-2 section href" data-target="{{route('admin.report.pos.sales')}}">
        <span class="icon">
            <i class="zmdi zmdi-money-box"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
           POS Sales Report
        </span>
    </div>
    

    <div class="col-md-2 section href" data-target="{{route('admin.report.dis')}}">
        <span class="icon">
            <i class="zmdi zmdi-truck"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Distributor
            <br>
            Report
        </span>
    </div>
    {{-- <div class="col-md-2 section href" data-target="{{route('admin.report.emp')}}">
        <span class="icon">
            <i class="zmdi zmdi-truck"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Employee <br> Report
        </span>
    </div> --}}
    {{-- <div class="col-md-2 section href" data-target="{{route('admin.report.credit')}}">
        <span class="icon">
            <i class="zmdi zmdi-money-off"></i>

        </span>
        <span class="divider"></span>
        <span class="text">
            Credit <br> Report
        </span>
    </div> --}}

    <div class="col-md-2 section href" data-target="{{route('admin.report.expense')}}">
        <span class="icon">
            <i class="zmdi zmdi-money-off"></i>

        </span>
        <span class="divider"></span>
        <span class="text">
            Expenses <br> Report
        </span>
    </div>
</div>
@endsection
@section('js')

@endsection
