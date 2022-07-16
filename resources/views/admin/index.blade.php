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
<h5>
    General Report of {{_nepalidate($today)}}
</h5>

<hr>
<div class="row" id="datas">
    @include('admin.index.milk')
    @include('admin.index.sales')
</div>
@endsection
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
