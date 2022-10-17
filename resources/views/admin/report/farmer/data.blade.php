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
@if ($newsession)
    <form action="{{ route('admin.report.farmer.session') }}" method="POST">
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="session" value="{{ $session }}">
        <input type="hidden" name="center_id" value="{{ $center->id }}">
@endif
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
$protsahan_amounttotal = 0;
$transport_amounttotal = 0;
$extratotal = 0;

$_tctotal = 0;
$_cctotal = 0;
$_grandtotal = 0;
$_milktotal = 0;
$_bonustotal = 0;
$_totaltotal = 0;
$_duetotal = 0;
$_advancetotal = 0;
$_prevduetotal = 0;
$_nettotaltotal = 0;
$_balancetotal = 0;
$_prevbalancetotal = 0;
$_paidamounttotal = 0;
$_fpaidtotal = 0;
$_protsahan_amounttotal = 0;
$_transport_amounttotal = 0;
$_extratotal = 0;

@endphp


@csrf

@foreach ($datas as $data)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Milk (l)</th>
                <th>Snf%</th>
                <th>Fat%</th>
                <th>Price/l</th>
                @if ($usecc || $usetc || $useprotsahan || $usetransport)
                    <th>MilK Total</th>
                    @if (env('farmer_report_milk_extra', 0) == 1)
                        @if ($usetc)
                            <th>TS</th>
                        @endif
                        @if ($usecc)
                            <th>Cooling <br>  Cost</th>
                        @endif
                        @if ($useprotsahan)
                            <th>Protsahan  <br> Amount</th>
                        @endif
                        @if ($usetransport)
                            <th>Transport <br>  Amount</th>
                        @endif
                    @else
                        <th>Extra <br>  Amount</th>
                    @endif
                @endif
                <th>Total</th>
                @if (env('hasextra', 0) == 1)
                    <th>Bonus({{ round($center->bonus, 2) }}%)</th>
                @endif
                <th>Purchase</th>
                <th>Purchase <br>  Paid</th>
                @if ($jinsiMilan)
                   <th>
                    Jinsi<br>Milan
                    </th> 
                @endif
                <th>Advance</th>
                <th>Prev <br>  Balance</th>
                <th>Prev <br>  Due</th>
                <th>Milk <br>
                    Payment
                </th>
                <th>Net <br>  Total</th>
                <th>Due <br> Balance</th>
                <th>Signature</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data['farmers'] as $farmer)
                <tr>
                    <td>
                        {{ $farmer->no }}
                        @if ($farmer->old == false && $newsession)
                            <input type="hidden" name="farmers[]" value='{!! json_encode($farmer) !!}'>
                        @endif
                    </td>
                    @php
                        $t = 'farmer-' . $farmer->id;
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
                    @if ($usecc || $usetc || $useprotsahan || $usetransport)
                        <td>
                            {{ $farmer->total }}
                            @php
                                $totaltotal += $farmer->total;
                            @endphp
                        </td>
                        @if (env('farmer_report_milk_extra'))
                            @if ($usetc)
                                <td>
                                    {{ $farmer->tc }}
                                    @php
                                        $tctotal += $farmer->tc;
                                    @endphp
                                </td>
                            @endif
                            @if ($usecc)
                                <td>
                                    {{ $farmer->cc }}

                                    @php
                                        $cctotal += $farmer->cc;
                                    @endphp
                                </td>
                            @endif
                            @if ($useprotsahan)
                                <td>{{ $farmer->protsahan_amount }}</td>
                                @php
                                    $protsahan_amounttotal += $farmer->protsahan_amount;
                                @endphp
                            @endif
                            @if ($usetransport)
                                <td>{{ $farmer->transport_amount }}</td>
                                @php
                                    $transport_amounttotal += $farmer->transport_amount;
                                @endphp
                            @endif
                        @else
                            <td>
                                @php
                                    $extra = $farmer->tc + $farmer->cc + $farmer->protsahan_amount + $farmer->transport_amount;
                                    $extratotal += $extra;
                                @endphp
                                {{ $extra }}
                            </td>
                        @endif
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

                    @if ($jinsiMilan)
                        <td>

                            {{$farmer->jinsiMilan}}
                        </td>
                    @endif
                    <td>
                        {{ $farmer->advance }}
                        {{-- <input type="hidden" name="advance[{{$t}}]" value="{{($farmer->advance)}}" > --}}
                        @php
                            $advancetotal += $farmer->advance;
                        @endphp
                    </td>
                    <td>
                        {{ $farmer->prevbalance }}
                        @php
                            $prevbalancetotal += $farmer->prevbalance;
                        @endphp
                    </td>
                    <td>
                        {{ $farmer->prevdue }}
                        {{-- <input type="hidden" name="prevdue[{{$t}}]" value="{{($farmer->prevdue)}}" > --}}
                        @php
                            $prevduetotal += $farmer->prevdue;
                        @endphp
                    </td>

                    <td>
                        {{ $farmer->paidamount }}
                        @php
                            $paidamounttotal += $farmer->paidamount;
                        @endphp
                    </td>
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
                @if ($usecc || $usetc || $useprotsahan || $usetransport)
                    <td>
                        {{ $totaltotal }}
                      
                    </td>
                    @if (env('farmer_report_milk_extra'))
                        @if ($usetc)
                            <td>
                                {{ $tctotal }}

                            </td>
                        @endif
                        @if ($usecc)
                            <td>
                                {{ $cctotal }}
                            </td>
                        @endif
                        @if ($useprotsahan)
                            <td>{{ $protsahan_amounttotal }}</td>
                        @endif
                        @if ($usetransport)
                            <td>{{ $transport_amounttotal }}</td>
                        @endif
                    @else
                        <td>

                            {{ $extratotal }}
                        </td>
                    @endif
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
                    {{ $prevbalancetotal }}
                </td>
                <td>
                    {{ $prevduetotal }}
                </td>
                <td>
                    {{ $paidamounttotal }}
                </td>

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
    @if ($data['full'])
        <div class="fs"></div>
    @endif
    @php
        $_tctotal += $tctotal;
        $_cctotal += $cctotal;
        $_grandtotal += $grandtotal;
        $_milktotal += $milktotal;
        $_bonustotal += $bonustotal;
        $_totaltotal += $totaltotal;
        $_duetotal += $duetotal;
        $_advancetotal += $advancetotal;
        $_prevduetotal += $prevduetotal;
        $_nettotaltotal += $nettotaltotal;
        $_balancetotal += $balancetotal;
        $_prevbalancetotal += $prevbalancetotal;
        $_paidamounttotal += $paidamounttotal;
        $_fpaidtotal += $fpaidtotal;
        $_protsahan_amounttotal += $protsahan_amounttotal;
        $_transport_amounttotal += $transport_amounttotal;
        $_extratotal += $extratotal;
        
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
        $protsahan_amounttotal = 0;
        $transport_amounttotal = 0;
        $extratotal = 0;
    @endphp
@endforeach

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Milk (l)</th>
            <th>Snf%</th>
            <th>Fat%</th>
            <th>Price/l</th>
            @if ($usecc || $usetc || $useprotsahan || $usetransport)
                <th>MilK Total</th>
                @if (env('farmer_report_milk_extra', 0) == 1)
                    @if ($usetc)
                        <th>TS</th>
                    @endif
                    @if ($usecc)
                        <th>Cooling <br> Cost</th>
                    @endif
                    @if ($useprotsahan)
                        <th>Protsahan <br> Amount</th>
                    @endif
                    @if ($usetransport)
                        <th>Transport <br> Amount</th>
                    @endif
                @else
                    <th>Extra <br> Amount</th>
                @endif
            @endif
            <th>Total</th>
            @if (env('hasextra', 0) == 1)
                <th>Bonus({{ round($center->bonus, 2) }}%)</th>
            @endif
            <th>Purchase</th>
            <th>Purchase <br> Paid</th>
            <th>Advance</th>
            <th>Prev <br> Balance</th>
            <th>Prev <br> Due</th>
            <th>
                Milk <br>
                Payment
            </th>
            <th>Net <br> Total</th>
            <th>Due <br> Balance</th>

        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">Grandtotal</td>
            <td>{{ $_milktotal }}</td>
            <td>--</td>
            <td>--</td>
            <td>--</td>
            @if ($usecc || $usetc || $useprotsahan || $usetransport)
                <td>
                    {{ $_totaltotal }}
                  
                </td>
                @if (env('farmer_report_milk_extra'))
                    @if ($usetc)
                        <td>
                            {{ $_tctotal }}

                        </td>
                    @endif
                    @if ($usecc)
                        <td>
                            {{ $_cctotal }}
                        </td>
                    @endif
                    @if ($useprotsahan)
                        <td>{{ $_protsahan_amounttotal }}</td>
                    @endif
                    @if ($usetransport)
                        <td>{{ $_transport_amounttotal }}</td>
                    @endif
                @else
                    <td>

                        {{ $_extratotal }}
                    </td>
                @endif
            @endif
            <td>{{ $_grandtotal }}</td>
            @if (env('hasextra', 0) == 1)
                <td>{{ $_bonustotal }}</td>
            @endif
            <td>
                {{ $_duetotal }}
            </td>
            <td>
                {{ $_fpaidtotal }}
            </td>
            <td>
                {{ $_advancetotal }}
            </td>
            <td>
                {{ $_prevbalancetotal }}
            </td>
            <td>
                {{ $_prevduetotal }}
            </td>
            <td>
                {{ $_paidamounttotal }}
            </td>

            <td>
                {{ $_nettotaltotal }}
            </td>
            <td>
                {{ $_balancetotal }}
            </td>
            <td>

            </td>
        </tr>
    </tbody>
</table>


@if ($newsession && env('closeonreport', false))
    <div class="py-2 d-print-none">
        <label for=>Session Close Date</label>
        <input type="text" name="date" value="{{ _nepalidate($sessionDate) }}" id="closedate" readonly required>
    </div>
    <div class="py-2 d-print-none">
        <input type="submit" value="Update Session Data" class="btn btn-success">
    </div>
@endif
</form>
