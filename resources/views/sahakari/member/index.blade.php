@extends('sahakari.layouts.app')
@section('title', 'Members')
@section('head-title')
Members
@endsection
@section('css')

@endsection
@section('toobar')
@endsection
@section('content')

<div class="px-2">

    <div class="row justify-content-center">
       
        <div class="col-md-2 text-center">
            <input type="radio" name="is" id="all" class="switch" data-switch=".all" data-main=".mm" 
                value="1" checked>
            <br><label for="all" class="pl-0">All</label>
        </div>
        <div class="col-md-2 text-center">
            <input type="radio" name="is" id="is_farmer" class="switch" data-switch=".f" data-main=".mm" data-if="#all"
                value="1">
            <br><label for="is_farmer" class="pl-0">Farmer</label>
        </div>
        <div class="col-md-2 text-center">
            <input type="radio" name="is" id="is_distributer" class="switch" data-switch=".d" data-main=".mm" data-if="#all"
                value="1">
            <br><label for="is_distributer" class="pl-0">Distributer</label>
        </div>
        <div class="col-md-2 text-center">
            <input type="radio" name="is" id="is_supplier" class="switch" data-switch=".s" data-main=".mm" data-if="#all"
                value="1">
            <br><label for="is_supplier" class="pl-0">Supplier</label>
        </div>
        <div class="col-md-2 text-center">
            <input type="radio" name="is" id="is_customer" class="switch" data-switch=".c" data-main=".mm" data-if="#all"
                value="1">
            <br><label for="is_customer" class="pl-0">Customer</label>
        </div>
        <div class="col-md-2 text-center">
            <input type="radio" name="is" id="id_emp" class="switch" data-switch=".e" data-main=".mm" data-if="#all"
                value="1">
            <br><label for="is_emp" class="pl-0">Employee</label>
        </div>
    
    </div>
</div>

    
    <div class="sp-table">
        <div class="row">
            <div class="col-md-6 py-2">
                <input type="search" id="ss" class="form-control bg-white" placeholder="Search Member">
            </div>
            <div class="col-md-6 py-2 text-right">
                <a href="{{ route('sahakari.members.add') }}" class="btn btn-n btn-primary">Add New Member</a>
    
            </div>
        </div>
    <hr class="my-1">

        <table >
            <thead>
    
                <tr>
                    <th>Member No</th>
                    <th>Name</th>
                    {{-- <th class="f mm">Farmer No</th>
                    <th class="mm e">Salary</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody id="members">
                @foreach ($members as $member)
                    <tr data-name="{{$member->name}}" data-no="{{$member->member_no}}" class="mm 
                        all 
                        {{$member->is_farmer==1?' f':''}}
                        {{$member->is_distributer==1?' d':''}}
                        {{$member->is_customer==1?' c':''}}
                        {{$member->is_emp==1?' e':''}}
                        {{$member->is_supplier==1?' f':''}}">
                        <td class="st">
                            {{$member->member_no}}
                        </td>
                        <td >{{$member->name}}</td>
                        {{-- <td class="mm f">{{$member->is_farmer?$member->farmer->no:"NA"}}</td>
                        <td class="mm e">{{$member->is_emp?$member->employee->salary:"NA"}}</</td> --}}

                        <td class="end text-right">
                            <a href="" class="btn btn-success mr-1">Edit</a>
                            <a href="" class="btn btn-primary mr-1">Detail</a>
                        </td>
                    </tr> 
                    <tr>
                        <td colspan="3" class="line">
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>


@endsection
@section('js')
    <script>
        initTableSearch('ss','members',['name','no']);
    </script>
@endsection
