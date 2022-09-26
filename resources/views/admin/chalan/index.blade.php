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
<a href="{{ route('admin.chalan.item.sell') }}" class="btn btn-primary">Create Employee Chalan</a>
@endsection
@section('content')
<div class="row">
    <div class="col-md-3">
        <div>
            <input type="hidden" id="currentdate">
            <input type="text" placeholder="Search Employee" id="s_dis" class="form-control mb-3">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody id="distributors">
                @foreach($users as $user)

                    <tr style="cursor:pointer;" onclick="setData({{$user->id}})" id="employee-{{$user->id}}" data-name="{{ $user->name }}" class="searchable">
                        <td>
                            {{$user->id}}
                        </td>
                        <td>
                            {{$user->name}}
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-9 bg-light pt-2">
        <div id="dataList">

        </div>

    </div>
</div>

<!-- edit modal -->

@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>

<script>
    // $( "#x" ).prop( "disabled", true );
    initTableSearch('sId', 'sellDisDataBody', ['name']);
    initTableSearch('s_dis', 'distributors', ['name']);
    initTableSearch('isid', 'itemData', ['number','name']);

    function setData(emp_id){
    axios.post('{{ route('admin.chalan.item.list')}}',{employee_id:emp_id})
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
