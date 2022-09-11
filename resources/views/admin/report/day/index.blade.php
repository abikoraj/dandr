@extends('admin.layouts.app')
@section('title','Report - Day')
@section('css')

@endsection
@section('head-title')
<a href="{{route('admin.report.home')}}">Reports </a>
/ Day Reports
@endsection
@section('toobar')
    
@endsection
@section('content')
<div class="row">
    <div class="col-md-2 section href" data-target="{{route('admin.report.day.milk')}}">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Milk Report
        </span>
    </div>
</div>
@endsection
@section('js')

@endsection
