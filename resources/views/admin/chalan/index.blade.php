@extends('admin.layouts.app')
@section('title','Employee Chalan')
@section('head-title')
    Employee Chalan
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
<style>
    td,th{
            border:1px solid black !important;
        }
        table{
            width:100%;
            border-collapse: collapse;
        }
        thead {display: table-header-group;}
        tfoot {display: table-header-group;}
</style>
@endsection
@section('head-title','Distributer Sell')
@section('toobar')
@if (auth_has_per('15.02'))
    <a href="{{ route('admin.chalan.item.sell') }}" class="btn btn-primary">Create Employee Chalan</a>
@endif
@endsection
@section('content')
<div class="shadow mb-3 p-2">
    <div class="row">
        <div class="col-md-4">
            <input type="checkbox"  id="use_date" checked> <label for="use_date">Date</label>
            <input type="text" id="date" class="form-control calender">

        </div>
        <div class="col-md-4">
            <label for="employee_id">Employee</label>
            <select  id="employee_id" class="form-control ms">
                <option value="-1" >All</option>
                @foreach (getUsers(['employees'],['id','name']) as $emp)
                    <option value="{{$emp->id}}">{{$emp->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="loadData()">
                Load Data
            </button>
        </div>
    </div>
</div>
<div id="dataList">

</div>


@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>

<script>
    // $( "#x" ).prop( "disabled", true );
    initTableSearch('sId', 'sellDisDataBody', ['name']);
    initTableSearch('s_dis', 'distributors', ['name']);
    initTableSearch('isid', 'itemData', ['number','name']);

    function loadData(){
        const data={
            employee_id: $('#employee_id').val()
        };
        if($('#use_date')[0].checked){
            data.date=$('#date').val();
        }
    axios.post('{{ route('admin.chalan.item.list')}}',data)
        .then(function(response) {
            // console.log(response.data);
            $('#dataList').html(response.data);
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });

    }






</script>

@endsection
