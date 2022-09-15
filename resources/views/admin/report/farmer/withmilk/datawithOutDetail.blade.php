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
@csrf

@foreach ($datas as $data)
    @php
        
        $milk = 0;
        $total = 0;
        $extra = 0;
        $grandtotal = 0;
        $bonus = 0;
        $advance = 0;
        $balance = 0;
        $nettotal = 0;
        $duebalance = 0;
        $paid = 0;
        $daytotal = [];
        for ($i = $range[1]; $i <= $range[2]; $i++) {
            $day['day_' . $i] = 0;
        }
    @endphp

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                @for ($i = $range[1]; $i <= $range[2]; $i++)
                    <th>
                        {{ _nepalidate($i) }}
                    </th>
                @endfor
                <th>Milk (l)</th>

                <th>Price/l</th>
                @if ($usecc || $usetc || $useprotsahan || $usetransport)
                    <th>MilK Total</th>
                    <th>Extra <br> Amount</th>
                @endif
                <th>Total</th>
                @if (env('hasextra', 0) == 1)
                    <th>Bonus({{ round($center->bonus, 2) }}%)</th>
                @endif
                <th>Advance</th>
                <th>Balance</th>
                <th>Paid</th>
                <th>Net <br> Total</th>
                <th>Due <br> Balance</th>
                <th>Signature</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data['farmers'] as $farmer)
                @php
                    $milkdata = $milkdatas->where('user_id', $farmer->id);
                    
                @endphp
                <tr>
                    <td rowspan="2">
                        {{ $farmer->no }}
                        @if ($farmer->old == false && $newsession)
                            <input type="hidden" name="farmers[]" value='{!! json_encode($farmer) !!}'>
                        @endif
                    </td>
                    @php
                        $t = 'farmer-' . $farmer->id;
                    @endphp

                    <td rowspan="2">
                        {{ $farmer->name }}
                    </td>
                    @for ($i = $range[1]; $i <= $range[2]; $i++)
                        <td>
                            @php
                                $local = $milkdata->where('date', $i)->first();
                            @endphp
                            {{ $local != null ? (float) $local->morning : 0 }}
                        </td>
                    @endfor
                    <td rowspan="2">
                        {{ $farmer->milk }}
                        @php
                            $milk += $farmer->milk;
                        @endphp
                        {{-- <input type="hidden" name="milk[{{$t}}]" value="{{($farmer->m_milk+$farmer->e_milk)}}" > --}}

                    </td>

                    <td rowspan="2">
                        {{ $farmer->rate }}
                        {{-- <input type="hidden" name="rate[{{$t}}]" value="{{($farmer->rate)}}" > --}}

                    </td>
                    @if ($usecc || $usetc || $useprotsahan || $usetransport)
                        <td rowspan="2">
                            {{ $farmer->total }}
                            @php
                                $total += $farmer->total;
                            @endphp
                        </td>
                        <td rowspan="2">
                            @php
                                $farmerextra = $farmer->tc + $farmer->cc + $farmer->protsahan_amount + $farmer->transport_amount;
                                $extra += $farmerextra;
                            @endphp
                            {{ $farmerextra }}
                        </td>
                    @endif
                    <td rowspan="2">
                        {{ $farmer->grandtotal }}
                        @php
                            $grandtotal += $farmer->grandtotal;
                        @endphp
                        {{-- <input type="hidden" name="total[{{$t}}]" value="{{($farmer->total)}}" > --}}

                    </td>
                    @if (env('hasextra', 0) == 1)
                        <td rowspan="2">
                            {{ $farmer->bonus ?? 0 }}
                            @php
                                $bonus += $farmer->bonus;
                            @endphp
                        </td>
                    @endif
                    <td rowspan="2">
                        {{ $farmer->advancetotal }}
                        @php
                            $advance += $farmer->advancetotal;
                        @endphp
                    </td>
                    <td rowspan="2">
                        {{ $farmer->balancetotal }}
                        @php
                            $balance += $farmer->balancetotal;
                        @endphp
                    </td>
                    <td rowspan="2">
                        {{ $farmer->paidamount }}
                        @php
                            $paid += $farmer->paidamount;
                        @endphp
                    </td>

                    <td rowspan="2">
                        {{ $farmer->nettotal }}
                        {{-- <input type="hidden" name="nettotal[{{$t}}]" value=" {{$tt>0?$tt:0}}" > --}}
                        @php
                            $nettotal += $farmer->nettotal;
                        @endphp
                    </td>
                    <td rowspan="2">
                        {{ $farmer->balance }}
                        @php
                            $duebalance += $farmer->balance;
                        @endphp
                        {{-- <input type="hidden" name="balance[{{$t}}]" value=" {{$tt<0?(-1*$tt):0}}" > --}}
                    </td>
                    <td rowspan="2">

                    </td>

                </tr>
                <tr>
                    @for ($i = $range[1]; $i <= $range[2]; $i++)
                        <td>
                            @php
                                $local = $milkdata->where('date', $i)->first();
                                if ($local != null) {
                                    $day['day_' . $i] += $local->morning+$local->evening;
                                }
                            @endphp
                            {{ $local != null ? (float) $local->evening : 0 }}
                        </td>
                    @endfor
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                @for ($i = $range[1]; $i <= $range[2]; $i++)
                    <td>
                        {{ $day['day_' . $i] }}
                    </td>
                @endfor
                <td>{{ $milk }}</td>
                <td>
                    --
                </td>

                @if ($usecc || $usetc || $useprotsahan || $usetransport)
                    <td>
                        {{ $total }}

                    </td>

                    <td>

                        {{ $extra }}
                    </td>
                @endif
                <td>{{ $grandtotal }}</td>
                @if (env('hasextra', 0) == 1)
                    <td>{{ $bonus }}</td>
                @endif
                <td>
                    {{ $advance }}
                </td>
                <td>
                    {{ $balance }}
                </td>


                <td>
                    {{ $nettotal }}
                </td>
                <td>
                    {{ $duebalance }}
                </td>
                <td></td>
            </tr>

        </tbody>
    </table>
    @if ($data['full'])
        <div class="fs"></div>
    @endif
@endforeach



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
