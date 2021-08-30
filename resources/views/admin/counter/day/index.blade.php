@extends('admin.layouts.app')
@section('title', 'Counters Day Management')
@section('head-title')
    <a href="{{ route('admin.counter.home') }}">Counters</a> / Day Management
@endsection
@section('toobar')
@endsection
@section('content')


    <div>
        <form action="{{route('admin.counter.day.open')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-2 pr-0 d-flex align-items-center">

                    <label for="date" class="w-100 text-right">Date:</label>
                </div>
                <div class="col-md-4 pl-1">
                    <input type="text" class="form-control" name="date" id="date"
                        {{ $setting == null ? '' : ($setting->open ? 'disabled' : '') }} value="{{ $setting == null ? '' : ($setting->open ? _nepalidate($setting->date) : '') }}">
                </div>
                <div class="col-md-3 d-flex align-items-center">

                    <input {{ $setting == null ? '' : ($setting->open ? 'disabled' : '') }} {{ $setting == null ? '' : ($setting->open ? 'checked' : '') }} type="checkbox" name="direct" id="direct" class="mr-2" value="1"> <label for="direct"
                        class="mb-0 pb-0">Open Directly</label>

                </div>
                <div class="col-md-3 d-flex align-items-center">
                    @if ($setting == null)
                        <button class="btn btn-primary w-100">Open Day</button>
                    @else
                        @if ($setting->open)
                            <button class="btn btn-primary w-100">Close Day</button>
                        @else
                            <button class="btn btn-primary w-100">Open Day</button>

                        @endif
                    @endif
                </div>
            </div>
        </form>
    </div>
    <hr>


@endsection
@section('js')
    <script>
        lock = false;
        @if ($setting == null)
            setDate('date',true);
        @else
            @if (!$setting->open)
                setDate('date',true);
            @endif
        @endif
    </script>
@endsection
