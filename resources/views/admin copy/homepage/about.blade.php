@extends('admin.layouts.app')
@section('title','Home page')
@section('head-title','AboutUs')


@section('content')

<form action="{{ route('setting.about') }}" method="post">
    @csrf
    <div class="row">
        <input type="hidden" name="id" value="{{$about->id}}">
        <div class="col-md-12 mb-4">
            <input type="text" name="title1" class="form-control" value="{{ $about->title1 }}" required>
        </div>
        <div class="col-md-12 mb-4">
            <textarea name="desc1" cols="30" class="form-control" rows="10">{{ $about->desc1 }}</textarea>
        </div>

        <div class="col-md-12 mb-4">
            <input type="text" name="title2" class="form-control" value="{{ $about->title2 }}" required>
        </div>
        <div class="col-md-12 mb-4">
            <textarea name="desc2" cols="30" class="form-control" rows="10">{{ $about->desc2 }}</textarea>
        </div>

        <div class="col-md-12 mb-4">
            <input type="text" name="title3" class="form-control" value="{{ $about->title3 }}" required>
        </div>
        <div class="col-md-12 mb-4">
            <textarea name="desc3" cols="30" class="form-control" rows="10">{{ $about->desc3 }}</textarea>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary">Save Change</button>
        </div>
    </div>
</form>
@endsection
@section('js')

@endsection
