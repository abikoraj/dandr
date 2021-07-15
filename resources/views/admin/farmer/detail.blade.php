@extends('admin.layouts.app')
@section('title','Farmer-Details')
@section('css')
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
    <a href="{{route('admin.farmer.list')}}">Famers</a>/ Farmer Details - . {{$user->name}}
@endsection
@section('toobar')
@endsection
@section('content')
@php

@endphp
@include('admin.snf.update')
@include('admin.farmer.snippet.updatemilkdata')
{{-- @include('admin.farmer.snippet.updatesell')
@include('admin.farmer.snippet.updateledger') --}}

<div class="row">
    <div class="col-md-3">
        <select name="year" id="year" class="form-control show-tick ms select2 load-year">
        </select>
    </div>
    <div class="col-md-3">
        <select name="month" id="month" class="form-control show-tick ms select2 load-month">
        </select>
    </div>
    <div class="col-md-3">
        <select name="session" id="session" class="form-control show-tick ms select2 load-session">
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <div class="col-md-3">
        <span class="btn btn-primary" onclick="loadData()"> Load </span>
        <span class="btn btn-primary" onclick="printDiv('print')"> Print </span>
    </div>
</div>
<div id="allData">

</div>
@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script>
    



    function loadData(){

        var user = {{ $user->id }};
        console.log(user);
        var data={
            'user_id':user,
            'year':$('#year').val(),
            'month':$('#month').val(),
            'session':$('#session').val(),
        };
        axios({
                method: 'post',
                url: '{{ route("admin.farmer.load-session-data") }}',
                data:data ,
        })
        .then(function(response) {
            $('#allData').html(response.data);

            setDate('closedate');
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });
    }

    window.onload = function() {

        loadData();
    };

    function snfUpdated(data){
        loadData();
    }

    function snfDeleted(){
        datadData();
    }
     function milkUpdated(data){
        loadData();
    }

    function milkDeleted(data){
        loadData();
    }
</script>
@endsection
