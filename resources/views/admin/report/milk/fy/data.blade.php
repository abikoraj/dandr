

    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">
                    Collection Center
                </th>
                @foreach ($monthArray as $month)
                    <th colspan="3">
                        {{ $month[1] }}
                    </th>
                @endforeach
                <th rowspan="2">
                    Total Milk
                </th>
                <th rowspan="2">
                    Total Milk Aount
                </th>
            </tr>
            <tr>
             @foreach ($monthArray as $month)
                <th>Total</th>
                <th>Milk Amount</th>
                <th>Per Liter</th>
             @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($centers as $center)
                <tr>
                   <td> {{ $center->name }} </td>
                   @foreach ($monthArray as $month)
                       @php
                           $data=$datas->where('year',$month[2][0])->where('month',$month[2][1])->where('center_id',$center->id)->first();
                       @endphp
                       @if ($data!=null)
                       <td>{{$data->milk}}</td>
                       <td>{{$data->amount}}</td>
                       <td>{{truncate_decimals($data->amount/$data->milk)}}</td>
                       @else
                       <td></td>
                       <td></td>
                       <td></td>
                       @endif
                   @endforeach
                   <td>{{ $datas->where('center_id',$center->id)->sum('milk') }}</td>
                   <td>{{ $datas->where('center_id',$center->id)->sum('amount') }}</td>

                </tr>
            @endforeach
           <tr>
               <th>Total</th>
               @foreach ($monthArray as $month)
               @php
                   $totalmilk = $datas->where('year',$month[2][0])->where('month',$month[2][1])->sum('milk');
                   $totalamount = $datas->where('year',$month[2][0])->where('month',$month[2][1])->sum('amount');
               @endphp
                <th>
                    {{ $totalmilk }}
                </th>
                <th>
                    {{ $totalamount }}
                </th>
                @if ($totalmilk!=0)
                <th>{{ truncate_decimals($totalamount/$totalmilk) }} </th>
                @else
                <th></th>
                @endif
               @endforeach
                <th></th>
           </tr>
        </tbody>

    </table>

