<hr>
<div id="print" class="p-2">
    <div class="p-3">
        <h3 class="text-center">{{ env('APP_NAME') }}</h3>
        <div style="font-weight: 800" class="d-flex justify-content-start">

            <span class="mr-4">
                Farmer No : {{ $farmer->no }}
            </span>
            <span class="mr-4">

                Name : {{ $farmer->name }}
            </span>
            <span class="mr-4">
                Phone no : {{ $farmer->phone }}
            </span>
        </div style="font-weight: 800">
        <div style="font-weight: 800" class="d-flex justify-content-start">
            <span class="mr-4">

                Year : {{ $farmer->session[0] }}
            </span>
            <span class="mr-4">

                Month : {{ $farmer->session[1] }}
            </span>
            <span class="mr-4">

                Session : {{ $farmer->session[2] }}
            </span>
        </div style="font-weight: 800">
    </div>
    <div class="row ">
        <div class="col-md-6">
            <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">
                {{-- <button class="btn btn-success" onclick="printDiv('milk-data');">Print</button> --}}
                <div id="milk-data">
                    <style>
                        td,
                        th {
                            border: 1px solid black;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        thead {
                            display: table-header-group;
                        }

                        tfoot {
                            display: table-header-group;
                        }
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
                                    <span class="d-none" id="milkdata-{{ $milk->id }}">
                                        {!! json_encode($milk) !!}
                                    </span>
                                    @if (auth_has_per('02.02'))
                                        <button class="btn btn-primary btn-sm"
                                            onclick="showMilkUpdateNew({{ $milk->id }})">
                                            Edit
                                        </button>
                                    @endif
                                    @if (auth_has_per('02.03'))
                                        <button class="btn btn-danger btn-sm"
                                            onclick="delMilkDataNew({{ $milk->id }});">
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
                            <td class="d-print-none"></td>

                        </tr>
                    </table>
                    <strong>Grand Total : {{ $farmer->milkamount }}</strong> (Liter) <br>

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
                            <th>Fats (%)</th>
                            <th>Snf (%)</th>
                            <td class="d-print-none">

                            </td>
                        </tr>
                        @foreach ($farmer->snfFats as $sf)
                            <tr>
                                <td>{{ _nepalidate($sf->date) }}</td>
                                <td>{{ $sf->fat }}</td>
                                <td>{{ $sf->snf }}</td>
                                <td class="d-print-none">

                                    @if (auth_has_per('02.05'))
                                        <span class="d-none"
                                            id="snffat-{{ $sf->id }}">{!! json_encode($sf) !!}</span>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="showSnfFatUpdateNew({{ $sf->id }})">
                                            Edit
                                        </button>
                                    @endif
                                    @if (auth_has_per('02.06'))
                                        <button class="btn btn-danger btn-sm"
                                            onclick="delSnfFatNew({{ $sf->id }});">
                                            delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <div style="display: flex">
                        <div style="flex:4;">
                            <strong>Snf Average : {{ round($farmer->snfavg, 2) }}</strong> <br>
                        </div>
                        <div style="flex:4;">
                            <strong>Fat Average : {{ round($farmer->fatavg, 2) }}</strong>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <table class="table table-borered">
                            <tr>
                                <th class="text-right">
                                    Milk Total
                                </th>
                                <th>
                                    {{$farmer->milkamount}} Liter
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right">
                                    Milk Rate
                                </th>
                                <th>
                                    Rs. {{$farmer->milkrate}}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right">
                                    Amount
                                </th>
                                <th>
                                    Rs. {{$farmer->total}}
                                </th>
                            </tr>
                            @if ($farmer->tc > 0 )

                                @if ($farmer->use_ts_amount)
                                <tr>
                                    <th class="text-right">

                                        +TS Commission (Rs.{{ (float) $farmer->ts_amount }} / Liter)
                                    </th>
                                    <th>
                                        Rs. {{ $farmer->tc }}
                                    </th>
                                </tr>

                                @else
                                <tr>
                                    <th class="text-right">

                                        +TS Commission ({{ (float) $center->tc }}%)
                                    </th>
                                    <th>
                                        {{ $farmer->tc }}
                                    </th>
                                </tr>
                                @endif
                            @endif
                            @if ($farmer->cc>0)
                            <tr>
                                <th class="text-right">

                                    +Cooling Cost(Rs. {{ (float) $center->cc }}/Liter)
                                </th>
                                <th>
                                    Rs. {{ $farmer->cc }}
                                </th>
                            </tr>
                            @endif
                            @if ($farmer->protsahan_amount>0)
                            <tr>
                                <th class="text-right">

                                    +Protsahan Amount (Rs. {{ (float) $farmer->protsahan }} / Liter)
                                </th>
                                <th>
                                    Rs. {{ $farmer->protsahan_amount }}
                                </th>
                            </tr>
                            @endif
                            @if ($farmer->transport_amount>0)
                            <tr>
                                <th class="text-right">

                                    +Transport Amount (Rs. {{ (float) $farmer->transport }} / Liter)
                                </th>
                                <th>
                                    Rs. {{ $farmer->transport_amount }}
                                </th>
                            </tr>
                            @endif
                            <tr>
                                <th class="text-right">
                                    Total Amount
                                </th>
                                <th>
                                    Rs. {{$farmer->grandtotal}}
                                </th>
                            </tr>
                        </table>
                        {{-- <strong>Milk Total : {{ $farmer->milkamount }} </strong><br>
                            <strong>Per Liter Rate : {{ $farmer->milkrate }} </strong> <br>
                            <strong>Amount : {{ $farmer->total }} </strong><br>
                            @if ($farmer->tc > 0 )
                                @if ($farmer->use_ts_amount)
                                    <strong>+TS Commission (Rs.{{ (float) $farmer->ts_amount }} / Liter) :
                                    {{ $farmer->tc }}</strong>
                                @else
                                    <strong>+TS Commission ({{ (float) $center->tc }}%) : {{ $farmer->tc }}</strong>
                                @endif
                                <br>
                            @endif

                            @if ($farmer->cc > 0)
                                <strong>+Cooling Cost: {{ $farmer->cc }}</strong> <br>
                            @endif
                            @if ($farmer->protsahan_amount > 0)
                                <strong>+Protsahan Amount ({{ (float) $farmer->protsahan }} / Liter):
                                    {{ $farmer->protsahan_amount }}</strong> <br>
                            @endif
                            @if ($farmer->transport_amount > 0)
                                <strong>+Transport Amount ({{ (float) $farmer->transport }} / Liter):
                                    {{ $farmer->transport_amount }}</strong>
                            @endif
                            <hr> --}}
                            {{-- <strong>Total Amount: {{ $farmer->grandtotal }}</strong> --}}
                    </div>
                </div>
            </div>
        </div>


        @include('admin.farmer.passbook.ledger')
        @include('admin.farmer.passbook.summary')


    </div>
    @if ($farmer->nettotal > 0 && $farmer->paidamount == 0)
        <div>
            @include('admin.farmer.passbook.payment')
        </div>
    @endif
</div>
