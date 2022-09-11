@extends('admin.layouts.app')
@section('title','Report')
@section('css')
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
    <a href="{{route('admin.report.home')}}">Report</a> / Farmer

@endsection
@section('toobar')

@endsection
@section('content')
<div id="number_search_checkbox" class="mb-5">
    <input type="checkbox" onclick="
    if(this.checked){
       $('.numsearch').removeClass( 'd-none' ).addClass( 'd-block' );
    }else{
        $('.numsearch').removeClass( 'd-block' ).addClass( 'd-none' );
        $('#s_n').val('');
        $('#e_n').val('');
    }
    "> Search By Custom Farmer Number
</div>
<div class="row">
    <div class="col-md-3">
        <label for="date">Year</label>
        <select name="year" id="year" class="form-control show-tick ms select2">
        </select>
    </div>
    <div class="col-md-3">
        <label for="date">Month</label>
        <select name="month" id="month" class="form-control show-tick ms select2">
        </select>
    </div>
    @if (env('session_type',1)==1)    
        <div class="col-md-3">
            <label for="date">Session</label>
            <select name="session" id="session" class="form-control show-tick ms select2">
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
    @endif
    <div class="col-md-3">
        <div class="form-group">
            <label for="date">Collection Center</label>
            <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="session">
                <option></option>
                @foreach(\App\Models\Center::all() as $c)
                <option value="{{$c->id}}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

        <div class="col-md-3 numsearch d-none">
            <div class="form-group">
                <label> Starting Farmer Number </label>
                <input type="number" id="s_n" class="form-control" name="s_number">
            </div>
        </div>

        <div class="col-md-3 numsearch d-none">
            <div class="form-group">
                <label> Ending Farmer Number </label>
                <input type="number" id="e_n" class="form-control" name="e_number">
            </div>
        </div>


    <div class="col-md-6">
        <span class="btn btn-primary" onclick="loadData()"> Load Report</span>

        <span class="btn btn-success" onclick="printDiv('allData');"> Print</span>
    </div>
</div>
<div id="allData" style="overflow-x: scroll;">

</div>
@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>

<script>
    var month = Array.from(NepaliFunctions.GetBsMonths());
    var i =1;
    month.forEach(element => {
        $('#month').append('<option value="'+i+'">'+element+'</option>');
        i++;
    });

    var start_y = 2070;
    var now_yr = NepaliFunctions.GetCurrentBsYear();
    var now_yr1 = now_yr;
    for (let index = start_y; index < now_yr; index++) {
        $('#year').append('<option value="'+now_yr1+'">'+now_yr1+'</option>');
        now_yr1--;
    }

    function loadData(){

        if($('#center_id').val()==""){
            alert('Please Select Collection ceter');
            return;
        }

        var d={
            'year':$('#year').val(),
            'month':$('#month').val(),
            'session':$('#session').val(),
            'center_id':$('#center_id').val(),
            's_number' :$('#s_n').val(),
            'e_number' :$('#e_n').val()
        };
        axios.post("{{route('admin.report.farmer')}}",d)
        .then(function(response){
            $('#allData').html(response.data);
            var edit = document.getElementById("closedate");
            if(edit!=undefined){
                edit.nepaliDatePicker();
            }
            // edit.nepaliDatePicker();
        })
        .catch(function(error){
            console.log(error);
            alert('some error occured');
        });
    }

    window.onload = function() {

        var month = NepaliFunctions.GetCurrentBsDate().month;
        var year = NepaliFunctions.GetCurrentBsDate().year;
        var day =  NepaliFunctions.GetCurrentBsDate().day;

        $('#year').val(year).change();
        $('#month').val(month).change();
        if(day>15){
            $('#session').val(2).change();
        }else{
            $('#session').val(1).change();
        }

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
