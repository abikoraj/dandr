@extends('sahakari.layouts.app')
@section('title', 'Members')
@section('head-title')
Members
@endsection
@section('css')

@endsection
@section('toobar')
<a href="{{ route('sahakari.members.add') }}" class="btn btn-primary">Add New Member</a>
@endsection
@section('content')

<div class="row">
    <div class="col-md-6">
        <input type="search" class="form-control" placeholder="Search Member">
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-2">
        <input type="radio" name="is" id="all" class="switch" data-switch=".all" data-main=".mm"
            value="1" checked>
        <label for="all" class="pl-2">All</label>
    </div>
    <div class="col-md-2">
        <input type="radio" name="is" id="is_farmer" class="switch" data-switch=".f" data-main=".mm"
            value="1">
        <label for="is_farmer" class="pl-2">Farmer</label>
    </div>
    <div class="col-md-2">
        <input type="radio" name="is" id="is_distributer" class="switch" data-switch=".f" data-main=".mm"
            value="1">
        <label for="is_distributer" class="pl-2">Distributer</label>
    </div>
    <div class="col-md-2">
        <input type="radio" name="is" id="is_supplier" class="switch" data-switch=".s" data-main=".mm"
            value="1">
        <label for="is_supplier" class="pl-2">Supplier</label>
    </div>
    <div class="col-md-2">
        <input type="radio" name="is" id="is_customer" class="switch" data-switch=".s" data-main=".mm"
            value="1">
        <label for="is_customer" class="pl-2">Customer</label>
    </div>

</div>

    <hr>
    <table class="table">
        <tr>
            <th>Member No</th>
            <th>Name</th>
            <th></th>
        </tr>
        @foreach ($members as $member)
            <tr class="mm 
                all 
                {{$member->is_farmer==1?' f':''}}
                {{$member->is_distributer==1?' d':''}}
                {{$member->is_customer==1?' c':''}}
                {{$member->is_supplier==1?' f':''}}">
                <td>
                    {{$member->member_no}}
                </td>
                <td>{{$member->name}}</td>
                <td></td>
            </tr> 
        @endforeach
    </table>

@endsection
@section('js')

@endsection
