@extends('admin.layouts.app')
@section('title','Transport To')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Transfer To')
@section('toobar')
@endsection
@section('content')
<div class="row">
<div class="col-lg-12">
    {{-- <div class="pt-2 pb-2">
        <input type="text" id="sid" placeholder="Search">
    </div> --}}
    <form action="{{ route('admin.farmer.transporttoadd') }}" id="form_validation" method="POST" >
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label> Transport From </label>
                <select name="farmer" class="form-control ms" id="">
                    <option>------ Select Farmer ------- </option>
                    @foreach ($farmers as $farmer)
                        <option value="{{ $farmer->user_id}}">{{ $farmer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label> Transport To </label>
                <select name="distributor" class="form-control ms" id="">
                    <option>------ Select Distributor ------- </option>
                    @foreach ($distributers as $dis)
                        <option value="{{ $dis->user_id}}">{{ $dis->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mt-4">
                <div class="form-group">
                    <button class="btn btn-primary">Save Data</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>



@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script>
    initTableSearch('searchid', 'data', ['name']);
    // load by date



</script>
@endsection
