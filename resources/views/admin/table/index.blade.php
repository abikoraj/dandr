@extends('admin.layouts.app')
@section('title','Tables')
@section('css')
@endsection
@section('head-title','Tables')
@section('toobar')
@endsection
@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
@foreach ($sections as $section)
    <h4>{{$section->name}}</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="shadow p-3">
                <form action="{{route('admin.table.add')}}" method="post">
                    @csrf
                    <input type="hidden" name="section_id" value="{{$section->id}}">
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
        @foreach ($tables->where('section_id',$section->id)    as $table)
        <div class="col-md-4">
            <div class="shadow p-3">
                <form action="{{route('admin.table.edit')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$table->id}}">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" 
                            placeholder="Enter Table Name" value="{{$table->name}}" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-success">Save</button>
                        <a href="{{route('admin.table.del',['id'=>$table->id])}}" class="btn btn-danger" onclick="return confirm('Do you want to delete {{$table->name}} table'); ">Delete</a>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endforeach
<div class="row">
    <div class="col-md-12 bg-light">
        
    </div>
</div>
@endsection
@section('js')
   
@endsection
