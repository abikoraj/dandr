@extends('admin.layouts.app')
@section('title', 'Employee Chalan - print')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalans</a>
    / {{ $chalan->name }} / {{ _nepalidate($chalan->date) }} / print
@endsection
@section('css')
@endsection
@section('content')
    <div class="shadow p-3 mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="rows">Total Rows </label>
                <input type="number" name="rows" id="rows" value="30" class="form-control" oninput="pageChanged()">
            </div>
            
            <div class="col-md-4 d-flex align-items-end" onclick="printDiv('print-div')">
                <button class="btn btn-primary w-100">
                    Print
                </button>
            </div>
        </div>
        <div>

        </div>
        <hr>
        <div id="print-div">
            <style>
                .table-bordered td,.table-bordered th{
                    border:1px solid black !important;
                }
                @media print {
                    tr.page-break {
                        display: block;
                        page-break-before: always;
                        page-break-inside: avoid;

                    }

                    html, body {
                        width: 210mm !important;
                        height: 297mm !important;
                    }
                }

                @page {
                    size: A4;
                    margin: 20px;
                }

            </style>
            @php
                 $count = count($items);
                 $customerCount = count($customers);
            @endphp
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2">Customer</th>
                        @foreach ($items as $item)
                            <th rowspan="2"  style="width:{{60/$count}}%;">{{ $item->title }}</th>
                        @endforeach
                        <th rowspan="2">
                            Payment
                        </th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                    </tr>

                </thead>
                <tbody id="data">

               
                    @foreach ($customers as $customer)
                        <tr class="pt">
                            <td style="font-size: 13px;">
                                {{ $customer->name }}
                            </td>
                            <td style="font-size: 13px;">
                                {{ $customer->phone }}
                            </td>
                            @for ($i = 0; $i < $count; $i++)
                                <td>
                                </td>
                            @endfor
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="shadow p-3 mb-3">
        <div>
            <button class="btn btn-primary w-100" onclick="printDiv('print-detail')">
                Print
            </button>
        </div>
        <hr>
        <div id="print-detail">
            <style>
                .table-bordered td,.table-bordered th{
                    border:1px solid black !important;
                }
            </style>
            <table class="table table-bordered">
                <tr>
                    <th>Item</th>
                    <th>Rate</th>
                    <th>Qty</th>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <th>{{ $item->title }}</th>
                        <th>{{ $item->rate }}</th>
                        <th>{{ $item->qty }}</th>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection
@section('js')
    <script>
        const customerCount = {{ $customerCount }};
        const count = {{ $count }};
        var perPage = 30;
        var page = 1;
        var totalpage = 1;
        var extra = 0;
        console.log(customerCount);
        $(document).ready(function() {
            
            extra = (perPage - (customerCount)) ;
            renderExtra();
        });

        function pageChanged() {
         
            perPage = parseInt($('#rows').val());

            if (isNaN(perPage) || perPage<30) {
                perPage = 30;
            }


            extra = (perPage - customerCount) ;
            renderExtra();
        }

        function renderExtra() {
            $('.extra').remove();
            let html = '';
            for (let index = 0; index < extra; index++) {

                html += `<tr class="pt extra "><th></th><th></th>`;
                for (let j = 0; j < count ; j++) {
                    html += '<th>&nbsp;</th>';
                }
                html += '<th></th></tr>';
            }

            $('#data').append(html);
            $('.pt').css('height', "10mm");
        }
    </script>
@endsection
