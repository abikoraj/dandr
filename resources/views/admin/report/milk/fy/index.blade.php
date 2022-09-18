@extends('admin.layouts.app')
@section('title','Report - Expense')
@section('css')
<style>
    th{
        white-space: nowrap;
    }
</style>
@endsection
@section('head-title')
    <a href="{{route('admin.report.home')}}">Report</a> / Milk / Fiscal Year

@endsection
@section('toobar')

@endsection
@section('content')

<div class="row">
    <div class="col-md-4">
        <label for="fy">Fiscal Year</label>
        <select name="fy" id="fy" class="form-control ms"> 
            @foreach (getFiscalYears() as $fy)
                @php
                    $current=getFiscalYear();
                @endphp
                <option value="{{$fy->id}}" {{$fy->id==$current->id?'selected':''}}>
                    {{$fy->name}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary" onclick="loadData()">
            Load Data
        </button>
        <button class="btn btn-primary" onclick="printDiv('allData')">Print</button>
    </div>
</div>
<hr>

<div id="allData" class="table-responsive">

</div>
@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>

<script>
    
    function loadData(){

        axios.post('{{route('admin.report.expene.fy')}}',{
            fy:$('#fy').val()
        })
        .then((res)=>{
            $('#allData').html(res.data);
        })
    }

    window.onload = function() {
        loadData();
       
    };

    function printDiv(id)
    {
        var divToPrint=document.getElementById(id);
        var newWin=window.open('','Report');
        newWin.document.open();
        newWin.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"><link rel="stylesheet" href="{{ asset("backend/css/print.css") }}"></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();

    }

    function manageDisplay(element){
        type=$(element).val();
        $('.ct').addClass('d-none');
        $('.ct-'+type).removeClass('d-none');
    }
</script>
@endsection
