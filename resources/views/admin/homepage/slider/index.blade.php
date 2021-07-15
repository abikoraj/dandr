@extends('admin.layouts.app')
@section('title','Home page')
@section('head-title','Sliders')


@section('content')

<form action="{{ route('setting.sliders') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-4">
            <input type="text" name="heading" class="form-control" placeholder="Primary Title" required>
        </div>

        <div class="col-md-12 mb-4">
            <input type="text" name="title" class="form-control" placeholder="Secondary title" required>
        </div>

        <div class="col-md-12 mb-4">
            <input type="file" name="image" class="form-control" placeholder="Choose image" required>
        </div>

        <div class="col-md-12">
            <button class="btn btn-primary">Save Change</button>
        </div>
    </div>
</form>

<div class="pt-3">
    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Heading</th>
                <th>Title</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($slider as $s)
            <tr>
                <td>{{ $s->heading }}</td>
                <td>{{ $s->title }}</td>
                <td><img src="{{asset($s->image)}}" alt="" style="height: 100px;"></td>
                <td><a href="{{ route('setting.slider.del',$s->id)}}" class="btn btn-primary">Delete</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('js')

@endsection
