@extends('admin.layouts.app')
@section('title','Extra Income')
@section('head-title')
<a href="{{route('admin.accounting.extra.income.index')}}">
    Extra Income
</a>
/ Categories
@endsection
@section('content')

<div class="row">
    <div class="col-md-4 mb-2">
        <div class="shadow p-3">
            <form action="{{route('admin.accounting.extra.income.category.add')}}" method="post">
            @csrf
            <div>
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" required class="form-control">
            </div>
            <div class="pt-2">
                <button class="btn btn-primary">Add Category</button>
            </div>
            </form>
        </div>
    </div>
    @foreach ($cats as $cat)
        <div class="col-md-4 mb-2">
            <div class="shadow p-3">
                <form action="{{route('admin.accounting.extra.income.category.update')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$cat->id}}">
                <div>
                    <label for="name-{{$cat->id}}">Category Name</label>
                    <input type="text" name="name" id="name-{{$cat->id}}" value="{{$cat->name}}" class="form-control">
                </div>
                <div class="pt-2 d-flex justify-content-between">
                    <button class="btn btn-primary">Update</button>
                    @if ($cat->c==0)
                        <a href="{{route('admin.accounting.extra.income.category.del',['id'=>$cat->id])}}" class="btn btn-danger" onclick="return prompt('Enter yes to continue')=='yes';">Del</a>
                    @endif
                </div>
                </form>
            </div>
        </div>

    @endforeach
</div>

@endsection
