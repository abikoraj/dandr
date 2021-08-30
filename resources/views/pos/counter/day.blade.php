@extends('pos.layout.index')
@section('content')
    <div class="container py-4">
        <div class="card shadow p-3">
            <h4 class="text-center">
                Day Not Opened Contact Administrator.
            </h4>
            <hr>
            <h6 class="py-2 d-flex justify-content-center">
                {{-- <a href="{{route('pos.index')}}" class="mx-2">COUNTERS</a> --}}
                <a href="{{route('pos.index')}}" class="mx-2 ">GOTO POS INTERFACE </a>
            </h6>
        </div>
    </div>
@endsection
