<hr>
<div id="print" class="p-2">
    <div class="p-3">
        <div style="font-weight: 800" class="d-flex justify-content-start">
            <span class="mr-4">
                Farmer No : {{$farmer->no}}
            </span>
            <span class="mr-4">

                 Name : {{$farmer->name}}
            </span>
            <span class="mr-4">
                Phone no : {{$farmer->phone}}
            </span>
        </div style="font-weight: 800">
        <div style="font-weight: 800" class="d-flex justify-content-start">
            <span class="mr-4">

                Year : {{$farmer->session[0]}}
            </span>
            <span class="mr-4">

                Month : {{$farmer->session[1]}}
            </span>
            <span class="mr-4">

                Session : {{$farmer->session[2]}}
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
                            <th>Morning (L)</th>
                            <th>Evening (L)</th>
                            <td class="d-print-none"></td>
                        </tr>
                        @php
                            $m = 0;
                            $e = 0;
                        @endphp
                            @foreach ($farmer->milkData as $milk)
                            <tr>
                                <td>{{ _nepalidate($milk->date) }}</td>
                                <td>{{ $milk->m_amount }}</td>
                                <td>{{ $milk->e_amount }}</td>
                                
                                <td class="d-print-none">
                                    <span class="d-none" id="milkdata-{{$milk->id}}">
                                        {!!json_encode($milk)!!}
                                    </span>
                                    @if (auth_has_per('02.02'))
                                        <button class="btn btn-primary btn-sm" onclick="showMilkUpdateNew({{$milk->id}})">
                                            Edit
                                        </button>
                                    @endif
                                    @if (auth_has_per('02.03'))

                                    <button class="btn btn-danger btn-sm"  onclick="delMilkDataNew({{$milk->id}});">
                                        delete
                                    </button>
                                    @endif
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
                        <strong>Grand Total : {{$farmer->milkamount}}</strong> (Liter) <br>

                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">

                <div id="snffat-data">
                    
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
                            @foreach ($farmer->snfFats as $sf)
                                <tr>
                                <td>{{ _nepalidate($sf->date) }}</td>
                                    <td>{{ $sf->snf }}</td>
                                    <td>{{ $sf->fat }}</td>
                                    <td class="d-print-none">

                                    @if (auth_has_per('02.05'))
                                        <span class="d-none" id="snffat-{{$sf->id}}">{!!json_encode($sf)!!}</span>
                                        <button class="btn btn-primary btn-sm"  onclick="showSnfFatUpdateNew({{$sf->id}})">
                                            Edit
                                        </button>
                                    @endif
                                    @if (auth_has_per('02.06'))

                                        <button class="btn btn-danger btn-sm"  onclick="delSnfFatNew({{$sf->id}});">
                                            delete
                                        </button>
                                    @endif
                                    </td>
                                </tr>

                            @endforeach
                    </table>
                   
                    <div style="display: flex">
                        <div style="flex:8;padding:10px;">
                            <strong>Snf Average : {{ round($farmer->snfavg,2) }}</strong> <br>
                            <strong>Milk Total : {{ $farmer->milkamount }} </strong><br>
                            <strong>Per Liter Rate : {{ $farmer->milkrate }} </strong> <br>
                                <strong>Amount : {{ $farmer->total }} </strong><br>
                                @if ($farmer->usetc)
                                    <strong>+TS Commission ({{(float)($center->tc)}}%) : {{ $farmer->tc }}</strong> <br>
                                @endif
                                @if ($farmer->usecc)
                                    <strong>+Cooling Cost: {{ $farmer->cc }}</strong>
                                @endif
                                <hr>
                                <strong>Total Amount: {{$farmer->grandtotal}}</strong>
                        </div>
                        <div style="flex:4;padding:10px;">
                            <strong>Fat Average : {{ round($farmer->fatavg,2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       

        @include('admin.farmer.passbook.ledger')
        @include('admin.farmer.passbook.summary')

        
    </div>
    @if ($farmer->nettotal>0 && $farmer->paidamount==0 )
        <div>
            @include('admin.farmer.passbook.payment')
        </div>
    @endif
</div>
