<h4 class="text-center mb-1">
    Balance Sheet <br> from {{_nepalidate($range[1])}} to {{_nepalidate($range[2])}}
</h4>
<div class="d-flex justify-content-between">
    <strong>Dr</strong>
    <strong>Cr</strong>
</div>
<div class="d-flex">
    <div style="flex: 1">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th class="w-40">
                        Particular
                    </th>
                    <th class="w-10">Rs</th>
                </tr>
            </thead>
            <tbody id="plAccDataDR">
                @if ($trading->status=="loss")
                    <tr class="main">
                        <td>
                            To Gross Loss
                        </td>
                        <td>
                            {{$trading->loss}}
                        </td>
                    </tr>
                @endif
                @if ($plac->salary)
                    <tr class="main">
                        <td>
                            To Salaries
                        </td>
                        <td>
                            {{$plac->salary}}
                        </td>
                    </tr>
                @endif
                @foreach ($plac->expenses as $expense)
                    <tr class="main">
                        <td>
                            To {{$expense->name}}
                        </td>
                        <td>
                            {{$expense->amount}}
                        </td>
                    </tr>
                @endforeach
                @if ($plac->status=='profit')
                <tr class="main">
                    <td>To Net Profit</td>
                    <td>{{$plac->profit}}</td>
                </tr>
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
                    <th class="w-10">Rs</th>
                </tr>
            </thead>
            <tbody id="plAccDataCR">
                @if ($trading->status=="profit")
                    <tr class="main">
                        <td>
                            By Gross Profits
                        </td>
                        <td>
                            {{$trading->profit}}
                        </td>
                    </tr>
                @endif
                @foreach ($plac->incomes as $income)
                    <tr class="main">
                        <td>By {{$income->name}}</td>
                        <td>
                            {{$income->amount}}
                        </td>
                    </tr>
                @endforeach
                @if ($plac->status=='loss')
                    <tr class="main">
                        <td>By Net Loss</td>
                        <td>{{$plac->loss}}</td>
                    </tr>
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
