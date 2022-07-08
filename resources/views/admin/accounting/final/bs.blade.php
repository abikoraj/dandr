<h4 class="text-center mb-1">
    Balance Sheet <br> from {{_nepalidate($range[1])}} to {{_nepalidate($range[2])}}
</h4>
@php
    $bsl=0;
    $bsa=0;
@endphp
<div class="d-flex">
    <div style="flex: 1">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="w-40">
                        Liabilities
                    </th>
                    <th class="w-10">Amount</th>
                </tr>
            </thead>
            <tbody id="bsliabilities">
                @foreach ($bs->liabilities as $acc)
                    @php
                        $bsl+=$acc->amount;
                    @endphp
                    @if ($acc->amount>0)

                        @if ($acc->identifire=='2.1')
                            <tr class="main">
                                <td>
                                    {{$acc->name}}
                                </td>
                                <td>{{$acc->totalCapital}}</td>
                            </tr>
                            <tr class="sub">
                                <td>
                                    Capital
                                </td>
                                <td>
                                    {{$acc->amount}}
                                </td>
                            </tr>
                            <tr class="sub">
                                @if ($acc->status=="profit")
                                    <td>
                                        + Net Profit
                                    </td>
                                    <td>
                                        {{$acc->profit}}
                                    </td>
                                @elseif ($acc->status=="loss")
                                    <td>
                                        - Net Loss
                                    </td>
                                    <td>
                                        {{$acc->loss}}
                                    </td>
                                @endif


                            </tr>
                        @else
                            <tr class="main">
                                <td>
                                    {{$acc->name}}
                                </td>
                                <td>
                                    {{$acc->amount}}
                                </td>
                            </tr>
                            @foreach (subAccounts($acc->id) as $subacc)
                                @if ($subacc->amount>0)

                                <tr class="sub">
                                    <td>
                                        {{$subacc->name}}
                                    </td>
                                    <td>
                                        {{$subacc->amount}}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
                @if($bs->payableAmount>0)
                    @php
                        $bsl+=$bs->payableAmount;
                    @endphp
                    <tr class="main">
                        <td>
                            Accounts Payable

                        </td>
                        <td>
                            {{$bs->payableAmount}}
                        </td>
                    </tr>
                    @foreach ($bs->payable as $payable)
                        <tr class="sub">
                            <td>
                                {{$payable['title']}}
                            </td>
                            <td>
                                {{$payable['amount']}}
                            </td>
                        </tr>
                    @endforeach

                @endif
            </tbody>
        </table>
    </div>
    <div style="flex: 1">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="w-40">
                        Particular
                    </th>
                    <th class="w-10">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bs->assets as $acc)
                    @if ($acc->amount>0)
                        <tr class="main">
                            <td>
                                {{$acc->name}}
                            </td>
                            <td>
                                {{$acc->amount}}
                            </td>
                        </tr>
                        @if ($acc->identifire=="1.2")
                            @foreach ($acc->banks as $bank)
                                <tr class="sub">
                                    <td>
                                        {{$bank->name}}
                                    </td>
                                    <td>
                                        {{$bank->balance}}
                                    </td>
                                </tr>
                            @endforeach
                        @elseif ($acc->identifire=="1.4")
                            @foreach ($acc->assets as $subacc)
                            <tr class="sub">
                                <td>
                                    {{$subacc->name}}
                                </td>
                                <td>
                                    {{$subacc->amount}}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            @foreach (subAccounts($acc->id) as $subacc)
                            <tr class="sub">
                                <td>
                                    {{$subacc->name}}
                                </td>
                                <td>
                                    {{$subacc->amount}}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach

                @if ($bs->receivableAmount>0)
                    <tr class="main">
                        <td>Accounts Receivable</td>
                        <td>
                            {{$bs->receivableAmount>0}}
                        </td>
                    </tr>
                    @foreach ($bs->receivable as $receivable)
                        <tr class="sub">
                            <td>{{$receivable['title']}}</td>
                            <td>{{$receivable['amount']}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>

        </table>
    </div>
</div>
<div class="d-flex ">
    <div style="flex:1">
        <table class="table table-bordered">
            <tr class="main">
                <td class="w-40">Total</td>
                <td class="w-10">{{$plac->total}}</td>
            </tr>
        </table>
    </div>
    <div style="flex:1">
        <table class="table table-bordered">
            <tr class="main">
                <td class="w-40">Total</td>
                <td class="w-10">{{$plac->total}}</td>
            </tr>
        </table>
    </div>
</div>
