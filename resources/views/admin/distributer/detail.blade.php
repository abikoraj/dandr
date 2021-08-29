@extends('admin.layouts.app')
@section('title', 'Distributer Details')
@section('css')
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
    <a href="{{ route('admin.distributer.index') }}">Distributors</a> / Details / {{ $user->name }}
@endsection
@section('toobar')
@endsection
@section('content')
    <div>
        @include('admin.layouts.daterange')
        <div class="row">
            <div class="col-md-3">
                <span class="btn btn-primary" onclick="loadData()"> Load </span>
            </div>

        </div>

    </div>
    <div id="allData">

    </div>
    {{-- @include('admin.distributer.balance.change') --}}

    {{-- @include('admin.farmer.snippet.updateledger') --}}

@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
    <script>
        function loadData() {
            var user = {{ $user->id }};
            console.log(user);
            var data = {
                'year': $('#year').val(),
                'month': $('#month').val(),
                'session': $('#session').val(),
                'week': $('#week').val(),
                'center_id': $('#center_id').val(),
                'date1': $('#date1').val(),
                'date2': $('#date2').val(),
                'type': $('#type').val(),
                'user_id': {{ $user->id }}
            };
            axios({
                    method: 'post',
                    url: '{{ route('admin.distributer.detail.load') }}',
                    data: data,
                })
                .then(function(response) {
                    $('#allData').html(response.data);
                    if(exists('#milk-to-ledger-date')){
                        setDate('milk-to-ledger-date');
                    }
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        window.onload = function() {
            $('#type').val(0).change();
            loadData();
        };


        function milkToLedger(e,ele) {
            e.preventDefault();
            formData=new FormData(ele);
            showProgress('Adding Milk Data To Ledger');
            axios.post('{{route('admin.distributer.MilkData.addToLedger')}}',formData)
            .then((res)=>{
                hideProgress();
                loadData();
            })
            .catch((err)=>{
                hideProgress();
                console.log(err);
            })
        }
    </script>
@endsection
