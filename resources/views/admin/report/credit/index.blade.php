@extends('admin.layouts.app')
@section('title','Report - Credit')

@section('head-title')
    <a href="{{route('report.home')}}">Report</a> / Credit

@endsection

@section('content')
<style>
    td,th{
        border:1px solid black;
    }
    @media print {
        td{
            font-weight:700;
        }
    }
    table{
        width:100%;
        border-collapse: collapse;
    }
    thead {display: table-header-group;}
    tfoot {display: table-header-group;}


</style>
<div class="row">
    <div class="col-md-6">

        <span class="btn btn-success" onclick="printDiv('allData');"> Print</span>
    </div>
</div>
<div id="allData">
    <div class="row">

        <div class="col-md-6">

            <h4>Farmer Credit</h4>
            <table class="table">
                <tr>
                    <th>Farmer Name</th>
                    <th>
                        Credit Amount
                    </th>
                </tr>
                @php
                    $fctot=0;
                @endphp
                @foreach ($farmercredit as $fc)
                    <tr>
                        <td>
                            {{$fc->name}}  ({{$fc->no}})
                        </td>
                        <td>
                            {{$fc->amount}}
                            @php
                               $fctot+= $fc->amount;
                            @endphp
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th>
                        Total
                    </th>
                    <th>
                        {{$fctot}}
                    </th>
                </tr>
            </table>
        </div>
        <div class="col-md-6">

            <h4>Distributor Credit</h4>
            <table class="table">
                <tr>
                    <th>Distributor Name</th>
                    <th>
                        Credit Amount
                    </th>
                </tr>
                @php
                    $fctot=0;
                @endphp
                @foreach ($distributorcredit as $fc)
                    <tr>
                        <td>
                            {{$fc->name}}
                        </td>
                        <td>
                            {{$fc->amount}}
                            @php
                               $fctot+= $fc->amount;
                            @endphp
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th>
                        Total
                    </th>
                    <th>
                        {{$fctot}}
                    </th>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection

