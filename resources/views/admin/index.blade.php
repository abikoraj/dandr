@extends('admin.layouts.app')
@section('title','Dashboard')
@section('css')
@endsection
@section('content')
<script>
    datas=[];
    const range={!! json_encode($range) !!};
    console.log(range,'range');
</script>


@include('admin.modalmenu')
<hr>
<h5 class="d-flex justify-content-between">
    <span>

        General Report of {{_nepalidate($today)}}
    </span>
    <span>
        <a href="{{route('admin.summary.index')}}">View More </a>
    </span>
</h5>

@if (env('use_farmer',false))
    
    <hr>
    <div class="row" id="datas">
        @include('admin.index.milk')
        @include('admin.index.sales')
    </div>
    @endsection
@endif
@section('js')
    <script src="{{asset('assets/js/chart.js')}}"></script>
    <script>
        $all=[];
        console.log(datas);
        $(document).ready(function () {
            datas.forEach(ele => {

            });
        });

    </script>








@endsection
