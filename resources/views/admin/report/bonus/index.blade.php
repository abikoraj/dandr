@extends('admin.layouts.app')
@section('title','Report')
@section('css')
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
    <a href="{{route('admin.report.home')}}">Report</a> / Bonus
@endsection
@section('toobar')

@endsection
@section('content')
<div class="row">

    <div class="col-md-3 ">
        <label for="date">From Year</label>
        <select name="year1" id="year1" class="form-control show-tick ms select2">
        </select>
    </div>
    <div class="col-md-3 ">
        <label for="date">From Month</label>
        <select name="month1" id="month1" class="form-control show-tick ms select2">
        </select>
    </div>
    <div class="col-md-3 ">
        <label for="date">To Year</label>
        <select name="year2" id="year2" class="form-control show-tick ms select2">
        </select>
    </div>
    <div class="col-md-3 ">
        <label for="date">To Month</label>
        <select name="month2" id="month2" class="form-control show-tick ms select2">
        </select>
    </div>

    <div class="col-md-3 ">
        <div class="form-group">
            <label for="date">Collection Center</label>
            <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="session">
                @foreach(\App\Models\Center::all() as $c)
                <option value="{{$c->id}}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>


</div>
<div class="row">
    <div class="col-md-12">
        <span class="btn btn-primary" onclick="loadData()"> Load Report</span>
        <span class="btn btn-danger" onclick="$('#allData').html('');$('#type').val(-1);manageDisplay($('#type')[0])"> Reset</span>
        <span class="btn btn-success" onclick="printDiv('allData');"> Print</span>
    </div>

</div>
<div id="allData">

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
        $('#month1').append('<option value="'+i+'">'+element+'</option>');
        $('#month2').append('<option value="'+i+'">'+element+'</option>');
        i++;
    });

    var start_y = 2070;
    var now_yr = NepaliFunctions.GetCurrentBsYear();
    var now_yr1 = now_yr;
    for (let index = start_y; index < now_yr; index++) {
        $('#year1').append('<option value="'+now_yr1+'">'+now_yr1+'</option>');
        $('#year2').append('<option value="'+now_yr1+'">'+now_yr1+'</option>');
        now_yr1--;
    }

    function loadData(){

        if($('#type').val()==-1){
            alert('Please Select Report Duration ');
            return;
        }

        var d={
            'year1':$('#year1').val(),
            'month1':$('#month1').val(),
            'year2':$('#year2').val(),
            'month2':$('#month2').val(),
            'center_id':$('#center_id').val(),
            'detailed':document.getElementById('detailed').checked?1:0
        };
        axios.post("{{route('admin.report.bonus')}}",d)
        .then(function(response){
            $('#allData').html(response.data);
        })
        .catch(function(error){
            alert('some error occured');
        });
    }

    window.onload = function() {

        var month = NepaliFunctions.GetCurrentBsDate().month;
        var year = NepaliFunctions.GetCurrentBsDate().year;
        var day =  NepaliFunctions.GetCurrentBsDate().day;

        $('#year1').val(year).change();
        $('#month1').val(month).change();
        $('#year2').val(year).change();
        $('#month2').val(month).change();
        // if(day>15){
        //     $('#session').val(2).change();
        // }else{
        //     $('#session').val(1).change();
        // }
        // $('.calender').each(function(){
        //     this.nepaliDatePicker();
        //     var month = ('0'+ NepaliFunctions.GetCurrentBsDate().month).slice(-2);
        //     var day = ('0' + NepaliFunctions.GetCurrentBsDate().day).slice(-2);
        //     $(this).val(NepaliFunctions.GetCurrentBsYear() + '-' + month + '-' + day);
        // });
    };



    function manageDisplay(element){
        type=$(element).val();
        $('.ct').addClass('d-none');
        $('.ct-'+type).removeClass('d-none');
    }
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
