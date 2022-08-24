@extends('admin.layouts.app')
@section('title','Table - Sections')
@section('css')
@endsection
@section('head-title')
<a href="{{route('admin.table.index')}}">Tables </a>
/ Sections
@endsection
@section('toobar')
@endsection
@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="row">
    <div class="col-md-4">
        <div class="shadow p-3">
            <form action="{{route('admin.table.section.add')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Section Name" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
    @foreach ($sections as $section)
    <div class="col-md-4">
        <div class="shadow p-3">
            <form action="{{route('admin.table.section.edit')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$section->id}}">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" 
                        placeholder="Enter Section Name" value="{{$section->name}}" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-success">Save</button>
                    <a href="{{route('admin.table.section.del',['id'=>$section->id])}}" class="btn btn-danger" onclick="return confirm('Do you want to delete {{$section->name}} section'); ">Delete</a>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
@section('js')
   
@endsection
