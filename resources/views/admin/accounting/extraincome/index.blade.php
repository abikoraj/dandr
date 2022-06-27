@extends('admin.layouts.app')
@section('title','Extra Income')
@section('head-title')
Extra Income
@endsection
@section('toobar')
<a href="{{route('admin.accounting.extra.income.add')}}" class="btn btn-primary">Add New Income</a>
    <a href="{{route('admin.accounting.extra.income.category')}}" class="btn btn-primary">Manage Categories</a>
@endsection
@section('content')
    <div class="shadow mb-3">
        <div class="p-3">
            <form action="{{route('admin.accounting.extra.income.index')}}">
                @csrf

                @include('admin.layouts.daterange',['alltext'=>'--'])
                <hr>
                <div >
                    <button class="btn btn-primary">Load Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
