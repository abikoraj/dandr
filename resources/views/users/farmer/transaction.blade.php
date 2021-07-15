@extends('users.farmer.layout.app')
@section('title','Farmer Transaction')
@section('head-title')
{{ Auth::user()->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-3 mb-2">
        <select name="year" id="year" class="form-control show-tick ms select2">
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <select name="month" id="month" class="form-control show-tick ms select2">
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <select name="session" id="session" class="form-control show-tick ms select2">
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <div class="col-md-3">
        <span class="btn btn-primary" onclick="loadData()"> Load </span>
    </div>
</div>
<div id="allData">

</div>

@endsection

@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
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

        var user = {{ Auth::user()->id }};
        console.log(user);
        var data={
            'user_id':user,
            'year':$('#year').val(),
            'month':$('#month').val(),
            'session':$('#session').val(),
        };
        axios({
                method: 'post',
                url: '{{ route("farmer.loaddata") }}',
                data:data ,
        })
        .then(function(response) {
            $('#allData').html(response.data);
        })
        .catch(function(response) {
            //handle error
            console.log(response);
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
        loadData();
    };
</script>
@endsection
