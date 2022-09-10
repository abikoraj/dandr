@extends('admin.layouts.app')
@section('title',"Accounts - Opening")
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ Accounts / {{$fy->name}} / Openings
@endsection
@section('content')
<div class="row">
    <div class="col-md-4">
        
    </div>
</div>
<hr>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>
                Account
            </th>
            <th>
                Opening Balance
            </th>
        </tr>
    </thead>
    @foreach ($openings as $opening)
        @php
            $acc=$accounts->where('id',$opening->id)->first();
        @endphp
        <tr>
            <th>
                {{$acc->name}}
            </th>
            <th>
                {{$opening->amount}}
            </th>
        </tr>
    @endforeach
</table>
@endsection