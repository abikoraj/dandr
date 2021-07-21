@extends('pos.layout.index')
@section('content')
    <div class="container py-4">

        <div class="card shadow p-3">
            <h4 class="text-center">Select Counter</h4>
            <hr>
            <div class="row">
                @foreach ($data as $counter)
                    <div class="col-md-4">
                        <form action="{{route('pos.counter')}}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{$counter->id}}">
                            <button class="bg-white shadow h-100 w-100 py-2 text-center d-flex justify-content-center">
                                {{$counter->name}}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection