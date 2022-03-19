
<hr>
<div id="print" class="p-2">
    <div class="p-3">
        <div style="font-weight: 800" class="d-flex justify-content-start">
            <span class="mr-4">
                Farmer No : {{$farmer1->no}}
            </span>
            <span class="mr-4">

                 Name : {{$farmer1->name}}
            </span>
            <span class="mr-4">
                Phone no : {{$farmer1->phone}}
            </span>
        </div style="font-weight: 800">
        <div style="font-weight: 800" class="d-flex justify-content-start">
            <span class="mr-4">

                Year : {{$data['year']}}
            </span>
            <span class="mr-4">

                Month : {{$data['month']}}
            </span>
            <span class="mr-4">

                Session : {{$data['session']}}
            </span>
        </div style="font-weight: 800">
    </div>
    <div class="row ">
        <div class="col-md-6">
            <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">
                {{-- <button class="btn btn-success" onclick="printDiv('milk-data');">Print</button> --}}
                <div id="milk-data">
                    <style>
                        td,th{
                            border:1px solid black;
                        }
                        table{
                            width:100%;
                            border-collapse: collapse;
                        }
                        thead {display: table-header-group;}
                        tfoot {display: table-header-group;}
                    </style>
                    <strong>Milk Data</strong>
                    <hr>
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <tr>
                            <th>Date</th>
                            <th>Morning (Liter)</th>
                            <th>Evening (liter)</th>
                            <td class="d-print-none"></td>
                        </tr>
                        @php
                            $m = 0;
                            $e = 0;
                        @endphp
                            @foreach ($milkData as $milk)
                            <tr>
                                <td>{{ _nepalidate($milk->date) }}</td>
                                <td>{{ $milk->m_amount }}</td>
                                <td>{{ $milk->e_amount }}</td>
                                <td class="d-print-none">
                                    <button class="btn btn-primary" data-milk="{{$milk->toJson()}}" onclick="showMilkUpdate(this)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" data-milk="{{$milk->toJson()}}" onclick="delMilkData(this);">
                                        delete
                                    </button>
                                </td>
                            </tr>
                            @php
                                $m += $milk->m_amount;
                                $e += $milk->e_amount;
                            @endphp
                            @endforeach
                            <tr>
                                <td><strong>Total</strong></td>
                                <td>{{ $m }}</td>
                                <td>{{ $e }}</td>

                            </tr>
                    </table>
                        <strong>Grand Total : {{ $m + $e }}</strong> (Liter) <br>
                        @php
                            $milkamount=$m+$e;
                        @endphp
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">

                <div id="snffat-data">
                    <style>
                        td,th{
                            border:1px solid black;
                        }
                        table{
                            width:100%;
                            border-collapse: collapse;
                        }
                        thead {display: table-header-group;}
                        tfoot {display: table-header-group;}
                    </style>
                    <strong>Snf & Fats </strong>
                    <hr>
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <tr>
                            <th>Date</th>
                            <th>Snf (%)</th>
                            <th>Fats (%)</th>
                            <td class="d-print-none">

                            </td>
                        </tr>
                            @foreach ($snfFats as $sf)
                                <tr>
                                <td>{{ _nepalidate($sf->date) }}</td>
                                    <td>{{ $sf->snf }}</td>
                                    <td>{{ $sf->fat }}</td>
                                    <td class="d-print-none">
                                        <button class="btn btn-primary" data-snffat="{{$sf->toJson()}}" onclick="showSnfFatUpdate(this)">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger" data-snffat="{{$sf->toJson()}}" onclick="delSnfFat(this);">
                                            delete
                                        </button>
                                    </td>
                                </tr>

                            @endforeach
                    </table>
                    <div style="display: flex">
                        <div style="flex:8;padding:10px;">
                            <strong>Snf Average : {{ round($snfAvg,2) }}</strong> <br>
                            <strong>Per Liter Rate : {{ round($perLiterAmount,2) }} </strong> <br>
                            @php
                                $milktotal=truncate_decimals(($m + $e) * $perLiterAmount,2);
                                if($tc==0 && $cc==0){
                                    $grandtotal=(int)$milktotal;
                                }else{
                                    $tctotal=truncate_decimals(($m + $e)*$tc,2);
                                    $cctotal=truncate_decimals(($m + $e)*$cc,2);
                                    $grandtotal=(int)($milktotal+$tctotal+$cctotal);

                                }
                            @endphp
                            @if ($cc>0||$tc>0)
                                <strong>Milk Total : {{ $milkamount }} </strong><br>
                                <strong>Amount : {{ $milktotal }} </strong><br>
                                <strong>+TS Commission ({{(float)($center->tc)}}%) : {{ $tctotal }}</strong> <br>
                                <strong>+Cooling Cost: {{ $cctotal }}</strong>
                                <hr>
                                <strong>Total Amount: {{$grandtotal}}</strong>


                            @else
                             <strong>Total Amount : {{ $grandtotal }} (Rs.)</strong>
                            @endif

                        </div>
                        <div style="flex:4;padding:10px;">
                            <strong>Fat Average : {{ round($fatAvg,2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12 mt-3">
            <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">
                <strong>Sold Items</strong>
                <hr>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <tr>
                        <th>Date</th>
                        <th>Item Name</th>
                        <th>Item Number</th>
                        <th>Rate</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                    </tr>
                       @php
                           $total = 0;
                           $paid = 0;
                           $due = 0;
                       @endphp
                       @foreach ($sellitem as $item)
                       <tr>
                           <td>{{ _nepalidate($item->date)}}</td>
                           <td>{{ $item->item->title }}</td>
                           <td>{{ $item->item->title }}</td>
                           <td>{{ $item->rate }}</td>
                           <td>{{ $item->qty }}</td>
                           <td>{{ $item->total }}</td>
                           <td>{{ $item->paid }}</td>
                           <td>{{ $item->due }}</td>
                       </tr>
                           @php
                               $total += $item->total;
                               $paid += $item->paid;
                               $due += $item->due;
                           @endphp
                       @endforeach
                        <tr>
                            <td colspan="7" class="text-right">Grand Total</td>
                            <td>{{ $total }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-right">Total Paid</td>
                            <td> {{ $paid }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-right">Total Due</td>
                            <td>{{ $due }}</td>
                        </tr>
                </table>
            </div>
        </div> --}}

        <div class="col-md-12 mt-3">
            <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">

                <div id="ledger-data">
                    <style>
                        @media print {
                            td{
                                font-size: 1.2rem !important;
                                font-weight: 600 !important;
                            }


                            th:last-child, td:last-child {
                                display: none;
                            }

                        }
                        td,th{
                            border:1px solid black !important;
                            padding:2px !important;
                            font-weight: 600 !important;
                        }

                        table{
                            width:100%;
                            border-collapse: collapse;
                        }
                        thead {display: table-header-group;}
                        tfoot {display: table-header-group;}
                    </style>
                    <strong>Ledger</strong>
                    <hr>
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <tr>
                            <th>Date</th>
                            <th>Particular</th>
                            <th>Cr. (Rs.)</th>
                            <th>Dr. (Rs.)</th>
                            <th>Balance (Rs.)</th>
                        </tr>

                        @foreach ($ledger as $l)
                            <tr>
                                <td>{{ _nepalidate($l->date) }}</td>
                                <td>{{ $l->title }}</td>

                                <td>
                                    @if ($l->type==1)
                                        {{ (float)$l->amount }}
                                    @endif
                                </td>
                                <td>
                                    @if($l->type==2)
                                    {{ (float)$l->amount }}
                                    @endif
                                </td>
                                <td>
                                    {{ $l->dr>0 ?"Dr. ".(float)$l->dr:""}}

                                    {{ $l->cr>0 ?"Cr. ".(float)$l->cr:"" }}

                                     {{($l->cr==0 || $l->cr==null) && ($l->dr==0 || $l->dr==null)?"--":""}}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


    <hr>
    <h5 class="font-weight-bold">
        Session Summary
    </h5>
    <hr>

    <div class="report p-2">
        <table class="table">
            <tr>
                <th>
                    Milk Amount
                </th>
                <th>
                    Fat
                </th>
                <th>
                    Snf
                </th>
                <th>
                    Rate
                </th>
                @if ($cc>0 || $tc>0)
                    <th>
                        Milk Total
                    </th>
                    @if ($milktotal>0)
                    <th>
                        TS %
                    </th>
                    <th>
                        Cooling <br>
                        cost
                    </th>
                    @endif
                @endif
                @if ($milktotal>0)
                <th>
                    Total
                </th>
                @endif
                @if (env('hasextra',0)==1)
                    <th>
                        Bonus ( {{ round($center->bonus,2) }} % )
                    </th>

                @endif
                @php

                    if (env('hasextra',0)==1){
                        $farmer1->bonus=(int)(round(($m + $e) * $perLiterAmount) * $center->bonus/100);
                    }
                @endphp

                <th>Due</th>
                <th>Avance</th>
                <th>
                    Prev Balance
                </th>
                <th>
                    Prev Due
                </th>
                <th>Net Total</th>
                <th>Due Balance</th>
                <th>

                </th>
            </tr>
            <tr>
                <td>
                    {{ $milkamount }}
                </td>
                <td>
                    {{ round($fatAvg,2) }}
                </td>
                <td>
                    {{ round($snfAvg,2) }}
                </td>
                <td>
                    {{ round($perLiterAmount,2) }}
                </td>
                @if ($tc>0||$cc>0)
                    <td>
                        {{$milktotal}}
                    </td>
                    @if ($milktotal>0)

                        <td>
                            {{$tctotal}}
                        </td>
                        <td>
                            {{$cctotal}}
                        </td>
                        <td>
                            {{$grandtotal}}
                        </td>
                    @endif
                    @else

                    <td>
                        {{ $grandtotal }}
                    </td>
                @endif
                @if(env('hasextra',0)==1)
                    <td>
                        {{ $farmer1->bonus??0}}
                    </td>
                @endif
                <td>
                    {{$farmer1->due}}
                </td>
                <td>
                    {{$farmer1->advance}}
                </td>
                <td>
                    {{  $farmer1->prevadvance}}
                </td>
                <td>
                    {{$farmer1->prevdue}}
                </td>
                @php
                    if($cc>0||$tc>0){
                        if($milktotal==0){
                            $tt=0-$farmer1->advance-$farmer1->due-$farmer1->prevdue-$farmer1->bonus+  $farmer1->prevadvance;

                        }else{

                            $tt=$grandtotal-$farmer1->advance-$farmer1->due-$farmer1->prevdue-$farmer1->bonus+  $farmer1->prevadvance;
                        }

                    }else{

                        $tt=(int)($grandtotal-$farmer1->advance-$farmer1->due-$farmer1->prevdue-$farmer1->bonus+$farmer1->prevadvance);
                    }
                    $balance=$tt<0?(-1*$tt):0;
                    $nettotal=$tt>0?$tt:0;
                @endphp
                <td>
                    {{$nettotal}}
                </td>
                <td>
                    {{$balance}}
                </td>
                <td>
                    @if ($farmer1->old==false)
                    <form action="{{route('report.farmer.single.session')}}" method="POST">
                        @csrf
                        <input type="hidden" name="year" value="{{$data['year']}}">
                        <input type="hidden" name="month" value="{{$data['month']}}">
                        <input type="hidden" name="session" value="{{$data['session']}}">
                        <input type="hidden" name="id" value="{{$farmer1->id}}">
                        <input type="hidden" name="snf" value="{{ round($snfAvg,2) }}">
                        <input type="hidden" name="fat" value="{{ round($fatAvg,2) }}">
                        <input type="hidden" name="rate" value=" {{ round($perLiterAmount,2) }}">
                        <input type="hidden" name="milk" value="{{ $milkamount }}">
                        <input type="hidden" name="total" value=" {{ $milktotal }}">
                        <input type="hidden" name="grandtotal" value=" {{ $milktotal==0?0:$grandtotal }}">
                        <input type="hidden" name="cc" value=" {{ $cctotal??0 }}">
                        <input type="hidden" name="tc" value=" {{ $tctotal??0 }}">
                        <input type="hidden" name="due" value=" {{ $farmer1->due}}">
                        <input type="hidden" name="bonus" value=" {{ $farmer1->bonus}}">
                        <input type="hidden" name="advance" value=" {{ $farmer1->advance }}">
                        <input type="hidden" name="prevdue" value=" {{ $farmer1->prevdue}}">
                        <input type="hidden" name="nettotal" value=" {{ $nettotal }}">
                        <input type="hidden" name="balance" value=" {{ $balance}}">
                        <input type="hidden" name="prevbalance" value=" {{ $farmer1->prevbalance}}">
                        <button class="btn btn-sm btn-success">Close Session</button>
                    </form>
                    @else
                        Session Closed
                    @endif
                </td>
            </tr>
        </table>
    </div>
