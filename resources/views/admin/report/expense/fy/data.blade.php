

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                @foreach ($monthArray as $month)
                    <th>
    
                        {{ $month[1] }}
                    </th>
                @endforeach
                <th>
                    Total
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $data = [];
                foreach ($monthArray as $month) {
                    $data[$month[1]] = 0;
                }
                
            @endphp
            @foreach ($cats as $cat)
                <tr>
                    <th>
                        {{ $cat->name }}
                    </th>
    
                    @foreach ($monthArray as $month)
                        @php
                            $expense = $expenses
                                ->where('month', $month[0])
                                ->where('id', $cat->id)
                                ->first();
                            $data[$month[1]] += $expense != null ? $expense->amount : 0;
                        @endphp
                        <td class="{{ $month[1] }}" data-value="{{ $expense != null ? $expense->amount : 0 }}">
                            {{(float) ($expense != null ? $expense->amount : 0) }}
                        </td>
                    @endforeach
                    <td class="total">
                        {{ $expense = $expenses->where('id', $cat->id)->sum('amount') }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th>
                    Salary
                </th>
                @php
                    $total=0;
                @endphp
                @foreach ($monthArray as $month)
                    @php
                        $salary = $salaries->where('month', $month[0])->first();
                        $data[$month[1]] += $salary != null ? $salary->amount : 0;
                        $total+=$salary != null ? $salary->amount : 0;
                    @endphp
                    <td class="{{ $month[1] }}" data-value="{{ $salary != null ? $salary->amount : 0 }}">
                        {{ (float)($salary != null ? $salary->amount : 0) }}
                    </td>
                @endforeach
                <td>
                    {{$total}}
                </td>
            </tr>
            <tr>
                <th>
                    Purchase Expenses
                </th>
                @php
                    $total=0;
                @endphp
                @foreach ($monthArray as $month)
                    @php
                        $purchaseExp = $purchaseExps->where('month', $month[0])->first();
                        $data[$month[1]] += $purchaseExp != null ? $purchaseExp->amount : 0;
                        $total+=$purchaseExp != null ? $purchaseExp->amount : 0;
                    @endphp
                    <td class="{{ $month[1] }}" data-value="{{ $purchaseExp != null ? $purchaseExp->amount : 0 }}">
                        {{ (float)($purchaseExp != null ? $purchaseExp->amount : 0) }}
                    </td>
                @endforeach
                <td>
                    {{$total}}
                </td>
            </tr>
      
            <tr>
                <th>
                    Total
                </th>
                @php
                    $total = 0;
                @endphp
                @foreach ($monthArray as $month)
                    <td>
                        @php
                            $total += $data[$month[1]];
                        @endphp
                        {{ $data[$month[1]] }}
                    </td>
                @endforeach
                <td>
                    {{ $total }}
                </td>
            </tr>
        </tbody>
    </table>

