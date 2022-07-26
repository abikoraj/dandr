@extends('admin.layouts.app')
@section('head-title','Daily Summary')
@section('title','Daily Summary')
@section('content')
    <div class="row mb-3">
        <div class="col-md-3"><label for="date">Date</label><input type="text" name="date" id="date" class="calender form-control"></div>
        <div class="col-md-3 pt-4">
            <button class="btn btn-primary" onclick="loadData();"> Load Data</button>
        </div>
    </div>
    <div class="" id="alldata">

    </div>
@endsection
@section('js')
    <script>
        function loadData() {
            const date=$('#date').val();
            showProgress('Loading Summary for '+date);
            axios.post("{{route('admin.summary.index')}}",{date:date})
            .then((res)=>{
                hideProgress();
                $('#alldata').html(res.data);
            })
            .catch((err)=>{
                hideProgress();
                showNotification("bg-danger","Some Error Occured");
            })
        }
    </script>
@endsection