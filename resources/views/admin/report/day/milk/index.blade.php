@extends('admin.layouts.app')
@section('title','Day Report - Milk')
@section('head-title','Day Report - Milk')
@section('content')
    <div class="row">
        <div class="col-3">
            <label for="date">Date</label>
            <input type="text" name="date" id="date" class="calender form-control">
        </div>
        <div class="col-3 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="loadData()">Load Data</button>
        </div>
    </div>
    <hr>
    <div id="allData" >

    </div>
    
@endsection
@section('js')
    <script>
        function loadData() { 
            axios.post("{{route('admin.report.day.milk')}}",{date:$('#date').val()})
            .then((res)=>{
                $('#allData').html(res.data);
            })
            .catch((err)=>{
                if(err.response){
                    showNotification('bg-danger',err.response.data.message);

                }else{
                    showNotification('bg-danger','Some error occured, please try again');
                }
            })
         }
    </script>
@endsection