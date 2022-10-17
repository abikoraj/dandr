@extends('admin.layouts.app')
@section('title', 'Distributer Change Password')
@section('head-title')
    <a href="{{ route('admin.distributer.index') }}">Distributors</a> / Change Password
@endsection

@section('content')
    <form action="{{ route('admin.distributer.changePassword') }}" method="post">
        @csrf
        <div class="row">
            <div class=" col-md-3">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control ms" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-md-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" minlength="6" required>
            </div>
            <div class="col-md-3 d-flex align-items-end ">
                <button class="btn btn-primary w-100">
                    Save
                </button>
            </div>
        </div>
    </form>

@endsection
