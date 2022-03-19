@extends('admin.layouts.app')
@section('title', 'Expense Categories')
@section('head-title')
    <a href="{{ route('admin.expense.index') }}">Expenses</a> / Categories
@endsection

@section('content')
@if (auth_has_per('06.02'))
    <div>
        <form action="{{ route('admin.expense.category.add') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group mb-2">
                        <label for="name">Expense Category Name</label>
                        <input type="text" id="name" name="name" class="form-control next" data-next="price"
                            placeholder="Expense Category Name" required>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <button class="btn btn-primary w-100" title="Saves Expenses Category">Save Category</button>
                </div>

            </div>
        </form>
    </div>
@endif
    <hr>
    <div class="row">
        @foreach (\App\Models\Expcategory::latest()->get() as $item)
            <div class="col-md-6 mb-3 " id="cat-{{$item->id}}">
                <div class="shadow p-2">

                    <form action="{{ route('admin.expense.category.update') }}" method="POST">
                        @csrf
                        <label for="name-{{$item->id}}">Name</label>
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <input type="text" name="name" id="name-{{$item->id}}" class="form-control" value="{{ $item->name }}">
                        <div class="d-flex">
                            @if (auth_has_per('06.03'))
                            <button class="btn btn-primary w-50">Update</button>
                            @endif
                            @if (auth_has_per('06.04'))
                            <span class="btn btn-danger w-50 text-white" onclick="delete()">Delete</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>


@endsection
