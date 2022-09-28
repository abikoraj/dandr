@extends('admin.layouts.app')
@section('title','Stock Report')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
    <a href="{{route('admin.report.home')}}">Report</a> / Stock

@endsection
@section('toobar')

@endsection
@section('content')
<div class="row">



    <div class="col-md-3 ">
        <div class="form-group">
            <label for="date">Collection Center</label>
            <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="session">
                <option value="-1">All</option>
                @foreach(\App\Models\Center::all() as $c)
                <option value="{{$c->id}}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <span class="btn btn-primary" onclick="loadData()"> Load Report</span>
        <span class="btn btn-danger" onclick="$('#allData').html('');"> Reset</span>
    </div>

</div>
<div id="allData">

</div>
@endsection
@section('js')

<script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>

<script>


    function loadData(){
        axios.post("{{route('admin.report.stock')}}",{center_id:$('#center_id').val()})
        .then(function(response){
            $('#allData').html(response.data);
        })
        .catch(function(error){
            alert('some error occured');
        });
    }

    window.onload = function() {


    };

    function printDiv(id)
    {
        var divToPrint=document.getElementById(id);
        var newWin=window.open('','Report');
        newWin.document.open();
        newWin.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"><link rel="stylesheet" href="{{ asset("backend/css/print.css") }}"></head><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

    }



</script>
@endsection
