@extends('admin.layouts.app')
@section('title', 'Manufacture Items')
@section('css')
    <style>
        .step-btn {
            cursor: pointer;
            padding: 10px 15px;
            flex: 1;
            text-align: center;
        }

        .steps-data{
            display: none;
        }

        .steps-data.active{
            display: block;
        }

        .shadow-local {
            box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        }

        .step-btn.active {
            background: rgb(0, 122, 204);
            color: white;
        }

        .step-div {
            display: none;

        }

        .step-div.active {
            display: block;
        }
    </style>
@endsection
@section('head-title', 'Manufacture Items')
@section('toobar')
    <a href="{{ route('admin.simple.manufacture.add') }}" class="btn btn-primary">Add New</a>
@endsection
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <form action="{{ route('admin.simple.manufacture.index') }}" method="post" onsubmit="return loadData(this,event);">
        @csrf
        @include('admin.layouts.daterange', ['alltext' => ''])
        <div class="">
            <button class="btn btn-success">
                Load Data
            </button>
        </div>
    </form>
    <div id="data">

    </div>
@endsection
@section('js')
    <script>
        var CurrentStep = 1;

        function refresh() {
            $('.steps').removeClass('active');
            $('.step-' + CurrentStep).addClass('active');
            $('.steps-data').removeClass('active');
            $('.step-data-' + CurrentStep).addClass('active');
        }

        function loadData(ele, e) {
            e.preventDefault();
            showProgress('Loading Data');
            axios.post(ele.action, new FormData(ele))
                .then((res) => {
                    $('#data').html(res.data);
                    hideProgress();
                    var CurrentStep = 1;

                })
                .catch((err) => {
                    console.log(err);
                    hideProgress();

                })
        }
    </script>
@endsection
