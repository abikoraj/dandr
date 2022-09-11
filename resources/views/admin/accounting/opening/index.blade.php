@extends('admin.layouts.app')
@section('title',"Accounts - Opening")
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ Accounts / {{$fy->name}} / Openings
@endsection
@section('content')
<form action="{{route('admin.accounting.opening.index')}}" id="add" >
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label for="date">Date</label>
            <input type="text" name="ate" id="date" value="{{$fy->startdate}}" class="calender form-control">
        </div>
        <div class="col-md-3">
            <label for="account_id">Account</label>
            <select name="account_id" id="account_id" class="form-control ms">
                @foreach ($accounts as $account)
                    
                    <option value="{{$account->id}}">{{$account->name}} ( {{$account->identifire}} )</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">
                Open Account
            </button>
        </div>
    </div>
</form>
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
@section('js')
    <script>
        $(document).ready(function () {
            $('#add').submit(function (e) { 
                e.preventDefault();
                showProgress('Opening Amount');
            });
        });
    </script>
@endsection