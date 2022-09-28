@extends('admin.layouts.app')
@section('title', 'Farmer-Details')
@section('css')
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
    <a href="{{ route('admin.farmer.list') }}">Famers</a>/ Farmer Details  <span id="farmername"></span>
@endsection
@section('toobar')
@endsection
@section('content')
    @php

    @endphp
    @include('admin.snf.update')
    @include('admin.farmer.snippet.updatemilkdata')
    {{-- @include('admin.farmer.snippet.updatesell')
@include('admin.farmer.snippet.updateledger') --}}

    <div style="position: relative;background:white;">
        <div style="position: fixed;padding:15px;top:170px;width:250px;bottom:50px;overflow-y: auto;background: white;"
            class="shadow">
            <div>
                <a href="{{ route('admin.farmer.passbook.notClosed') }}" class="btn btn-primary">
                    Session Not Closed Farmers
                </a>
            </div>
            <hr>
            <div class="pb-2">
                <select name="center_id" id="center_id" class="form-control ms">
                    {!! renderCenters($c) !!}
                </select>
            </div>
            <div class="pb-2">
                <input type="number" name="farmer_no" id="farmer_no" class="form-control" placeholder="Farmer No"
                    onkeydown="if(event.which==13){loadData();}" value="{{ $f }}">
            </div>
            <div class="pb-2">
                <select name="year" id="year" class="form-control show-tick ms select2 load-year">
                </select>
            </div>
            <div class="pb-2">
                <select name="month" id="month" class="form-control show-tick ms select2 load-month">
                </select>
            </div>

            @if (env('session_type', 1) == 1)
                <div class="pb-2">
                    <select name="session" id="session" class="form-control show-tick ms select2 load-session">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
            @endif
            <div class="pb-2">
                <span class="btn btn-primary" onclick="loadData()"> Load </span>
                <span class="btn btn-primary" onclick="printDiv('print')"> Print </span>
            </div>
        </div>
        <div style="margin-left: 265px">
            <div id="allData">

            </div>
        </div>
    </div>


@endsection
@section('js')
    <script src="{{ asset('backend/js/jquery.hotkeys.js') }}"></script>
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        var updateNeeded = false;

        function closeSession(e, ele) {
            e.preventDefault();
            if(!xpayVerifyData()){
                return;
            }
            showProgress("Saving Payment");
            axios.post("{{ route('admin.farmer.passbook.close') }}", new FormData(ele))
                .then((res) => {
                    console.log(res.data);
                    loadData();
                    hideProgress();
                })
                .catch((err) => {
                    loadData();
                    hideProgress();
                });
        }

        function update(ele) {
            updateNeeded = false;
            showProgress("Updating Sessiondata");
            axios.post("{{ route('admin.farmer.passbook.updateData') }}", new FormData(ele))
                .then((res) => {
                    console.log(res.data);
                    loadData();
                    hideProgress();
                })
                .catch((err) => {
                    loadData();
                    hideProgress();
                });
        }

        function updateSession(e, ele) {

            e.preventDefault();
            update(ele);
        }

        function loadData() {
            user = null;
            $('#allData').html(`<div class=" text-center">Loading Data</div>`);
            showProgress("Loading Data");

            var data = {
                'farmer_no': $('#farmer_no').val(),
                'center_id': $('#center_id').val(),
                'year': $('#year').val(),
                'month': $('#month').val(),
                'session': $('#session').val(),
            };
            axios({
                    method: 'post',
                    url: '{{ route('admin.farmer.passbook.data') }}',
                    data: data,
                })
                .then(function(response) {
                    $('#allData').html(response.data);
                    setDate('closedate');
                    addXPayHandle();
                    hideProgress();
                    if (updateNeeded) {
                        const ele = document.getElementById('updateSession');
                        if (ele != null) {
                            update(ele);
                        }
                        console.log(ele);
                    }
                })
                .catch(function(response) {
                    //handle error
                    $('#allData').html(`<div class="text-danger text-center">Error Loading Data</div>`);

                    hideProgress();
                    console.log(response);
                });
        }

        window.onload = function() {
            $('body').addClass('ls-toggle-menu');
            $('body,.form-control1, .form-control').bind('keydown', 'f1', function(e) {
                e.preventDefault();
                $('#farmer_no').focus();
                $('#farmer_no').select();
            });

            @if ($c != null && $f != null)
                loadData();
            @endif

        };

        function snfUpdated(data) {
            updateNeeded = true;
            loadData();
        }

        function snfDeleted() {
            updateNeeded = true;
            loadData();

        }

        function milkUpdated(data) {
            updateNeeded = true;
            loadData();
        }

        function milkDeleted(data) {
            updateNeeded = true;
            loadData();
        }
    </script>
@endsection
