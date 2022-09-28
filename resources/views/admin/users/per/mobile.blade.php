@extends('admin.layouts.app')
@section('title', 'User Permission')
@section('head-title')
    <a href="{{ route('admin.user.users') }}">
        Users
    </a>

    /Mobile Config
@endsection
@section('content')
    <form action="{{ route('admin.user.mobile.per', ['user' => $user->id]) }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12">
                <label for="">Identifier</label>
                
            </div>
        </div>
        <div class="pt-2">
            <button class="btn btn-primary">
                Save Config
            </button>
        </div>
    </form>

@endsection
@section('js')

@endsection
