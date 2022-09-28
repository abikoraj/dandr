@extends('admin.layouts.app')
@section('title', 'Employee Chalan - closing')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalans</a>
    / Dues
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('content')
<div class="row">
    <div class="col-3">
        <table class="table table-bordered">
            @foreach ($employees as $employee)
                <tr onclick="loadEmployee({{$employee->id}})" style="cursor: pointer;">
                    <th >
                        {{$employee->name}}
                    </th>
                </tr>

            @endforeach
        </table>
    </div>
    <div class="col-9" id="data" >

    </div>
</div>
@endsection
@section('js')
    <script>
        function loadEmployee(id){
            showProgress('load');
            axios.post("{{route('admin.employee.chalan.due.index')}}",{id:id})
            .then((res)=>{
                successAlert("Data loaded successfully");
                $('#data').html(res.data);
            })
            .catch((err)=>{
                errAlert(err);
            });
        }
    </script>

@endsection