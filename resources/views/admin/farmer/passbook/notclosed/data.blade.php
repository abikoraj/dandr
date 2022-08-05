<style>
    td,
    th {
        border: 1px solid black;
    }

    @media print {
        td {
            font-weight: 700;
        }
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
<h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
    {{ env('APP_NAME', 'Dairy') }}
</h2>

<div style="display: flex;justify-content: space-between;font-weight:800;">
    <span>
        Year : {{ $year }}

    </span>
    <span>
        Month : {{ $month }}
    </span>
    <span>
        Session : {{ $session }}
    </span>
    <span>
        Center : {{ $center->name }}
    </span>
</div>
<form action="{{ route('admin.farmer.passbook.close.notClosed') }}" method="POST"  onsubmit="return saveData(event,this);">
    <input type="hidden" name="year" value="{{ $year }}">
    <input type="hidden" name="month" value="{{ $month }}">
    <input type="hidden" name="session" value="{{ $session }}">
    <input type="hidden" name="center_id" value="{{ $center->id }}">
    @php
        $i = 1;
        $d = 0;
        $start = true;
        $point = false;
        
        $tctotal = 0;
        $cctotal = 0;
        $grandtotal = 0;
        $milktotal = 0;
        $bonustotal = 0;
        $totaltotal = 0;
        $duetotal = 0;
        $advancetotal = 0;
        $prevduetotal = 0;
        $nettotaltotal = 0;
        $balancetotal = 0;
        $prevbalancetotal = 0;
        $paidamounttotal = 0;
        $fpaidtotal = 0;
        
    @endphp


    @csrf
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Milk (l)</th>
                <th>Snf%</th>
                <th>Fat%</th>
                <th>Price/l</th>
                @if ($usecc || $usetc)
                    <th>MilK Total</th>
                    <th>TS</th>
                    <th>Cooling Cost</th>
                @endif
                <th>Total</th>
                @if (env('hasextra', 0) == 1)
                    <th>Bonus({{ round($center->bonus, 2) }}%)</th>
                @endif
                <th>Purchase</th>
                <th>Purchase Paid</th>
                <th>Advance</th>
                <th>Prev Due</th>
                @if (env('tier', 0) == 1)
                    <th>Prev Balance</th>
                    <th>Paid Amount</th>
                @endif
                <th>Net Total</th>
                <th>Due Balance</th>
                <th>Signature</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $farmer)
                <tr>
                    <td>
                        {{ $farmer->no }}
                        <input type="hidden" name="farmers[]" value='{!! json_encode($farmer) !!}'>

                    </td>
                    @php
                        $t='farmer-' . $farmer->id;
                    @endphp

                    <td>
                        {{ $farmer->name }}
                    </td>
                    <td>
                        {{ $farmer->milk }}
                        @php
                            $milktotal += $farmer->milk;
                        @endphp
                        {{-- <input type="hidden" name="milk[{{$t}}]" value="{{($farmer->m_milk+$farmer->e_milk)}}" > --}}

                    </td>
                    <td>
                        {{ $farmer->snf }}
                        {{-- <input type="hidden" name="snf[{{$t}}]" value="{{($farmer->snf)}}" > --}}

                    </td>
                    <td>
                        {{ $farmer->fat }}
                        {{-- <input type="hidden" name="fat[{{$t}}]" value="{{($farmer->fat)}}" > --}}

                    </td>
                    <td>
                        {{ $farmer->rate }}
                        {{-- <input type="hidden" name="rate[{{$t}}]" value="{{($farmer->rate)}}" > --}}

                    </td>
                    @if ($usecc || $usetc)
                        <td>
                            {{ $farmer->total }}
                            @php
                                $totaltotal += $farmer->total;
                            @endphp
                        </td>
                        <td>
                            {{ $farmer->tc }}
                            @php
                                $tctotal += $farmer->tc;
                            @endphp
                        </td>
                        <td>
                            {{ $farmer->cc }}

                            @php
                                $cctotal += $farmer->cc;
                            @endphp
                        </td>
                    @endif
                    <td>
                        {{ $farmer->grandtotal }}
                        @php
                            $grandtotal += $farmer->grandtotal;
                        @endphp
                        {{-- <input type="hidden" name="total[{{$t}}]" value="{{($farmer->total)}}" > --}}

                    </td>
                    @if (env('hasextra', 0) == 1)
                        <td>
                            {{ $farmer->bonus ?? 0 }}
                            @php
                                $bonustotal += $farmer->bonus;
                            @endphp
                        </td>
                    @endif
                    <td>
                        {{ $farmer->purchase }}
                        {{-- <input type="hidden" name="due[{{$t}}]" value="{{($farmer->due)}}" > --}}
                        @php
                            $duetotal += $farmer->purchase;
                        @endphp
                    </td>
                    <td>
                        {{ $farmer->fpaid }}
                        @php
                            $fpaidtotal += $farmer->fpaid;
                        @endphp
                    </td>

                    <td>
                        {{ $farmer->advance }}
                        {{-- <input type="hidden" name="advance[{{$t}}]" value="{{($farmer->advance)}}" > --}}
                        @php
                            $advancetotal += $farmer->advance;
                        @endphp
                    </td>
                    <td>
                        {{ $farmer->prevdue }}
                        {{-- <input type="hidden" name="prevdue[{{$t}}]" value="{{($farmer->prevdue)}}" > --}}
                        @php
                            $prevduetotal += $farmer->prevdue;
                        @endphp
                    </td>
                    @if (env('tier', 0) == 1)
                        <td>
                            {{ $farmer->prevbalance }}
                            @php
                                $prevbalancetotal += $farmer->prevbalance;
                            @endphp
                        </td>
                        <td>
                            {{ $farmer->paidamount }}
                            @php
                                $paidamounttotal += $farmer->paidamount;
                            @endphp
                        </td>
                    @endif
                    <td>
                        {{ $farmer->nettotal }}
                        {{-- <input type="hidden" name="nettotal[{{$t}}]" value=" {{$tt>0?$tt:0}}" > --}}
                        @php
                            $nettotaltotal += $farmer->nettotal;
                        @endphp
                    </td>
                    <td>
                        {{ $farmer->balance }}
                        @php
                            $balancetotal += $farmer->balance;
                        @endphp
                        {{-- <input type="hidden" name="balance[{{$t}}]" value=" {{$tt<0?(-1*$tt):0}}" > --}}
                    </td>

                    @if (env('paywhenupdate', 0) == 1)
                        <td>

                        </td>
                    @endif
                    <td>

                    </td>

                </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td>{{ $milktotal }}</td>
                <td>--</td>
                <td>--</td>
                <td>--</td>
                @if ($usecc || $usetc)
                    <td>
                        {{ $totaltotal }}

                    </td>
                    <td>
                        {{ $tctotal }}

                    </td>
                    <td>
                        {{ $cctotal }}

                    </td>
                @endif
                <td>{{ $grandtotal }}</td>
                @if (env('hasextra', 0) == 1)
                    <td>{{ $bonustotal }}</td>
                @endif
                <td>
                    {{ $duetotal }}
                </td>
                <td>
                    {{ $fpaidtotal }}
                </td>
                <td>
                    {{ $advancetotal }}
                </td>
                <td>
                    {{ $prevduetotal }}
                </td>
                @if (env('tier', 0) == 1)
                    <td>
                        {{ $prevbalancetotal }}
                    </td>
                    <td>
                        {{ $paidamounttotal }}
                    </td>
                @endif
                <td>
                    {{ $nettotaltotal }}
                </td>
                <td>
                    {{ $balancetotal }}
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <hr>
    <div>
        <input type="text" name="date" id="date" value="{{_nepalidate($closingDate)}}">
        <br>
        <button class="btn btn-success">
            Load Data into Ledger
        </button>
    </div>

</form>
