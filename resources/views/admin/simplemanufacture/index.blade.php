@extends('admin.layouts.app')
@section('title','Manufacture Items')
@section('css')
@endsection
@section('head-title','Manufacture Items')
@section('toobar')
<a href="{{route('admin.simple.manufacture.add')}}" class="btn btn-primary">Add</a>
@endsection
@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
@include('admin.layouts.daterange',['alltext'=>''])
<div class="row">
</div>
@endsection
@section('js')
   
@endsection
