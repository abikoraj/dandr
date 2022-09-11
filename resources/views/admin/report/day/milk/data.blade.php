<div class="shadow mb-3 p-2">
    <h5>
       Milk Summary of {{env('APP_NAME')}} for {{_nepalidate($date)}}
    </h5>
    <div class="row">
        <div class="col-md-2">
            <strong>
                Avg.  Snf
            </strong>
            <div>
                {{$summary->snf}}
            </div>
        </div>
        <div class="col-md-2">
            <strong>
                Avg.  Fat
            </strong>
            <div>
                {{$summary->fat}}
            </div>
        </div>
        <div class="col-md-2">
            <strong>
                Avg.  Rate
            </strong>
            <div>
                {{truncate_decimals($summary->grandtotal/$summary->milk)}}
            </div>
        </div>
        <div class="col-md-2">
            <strong>
                Total Milk
            </strong>
            <div>
                {{$summary->milk}}
            </div>
        </div>
        <div class="col-md-2">
            <strong>
               Total Amount
            </strong>
            <div>
                {{$summary->grandtotal}}
            </div>
        </div>
    </div>
</div>

@foreach ($datas as $key => $farmers)
    <h5>
        Center : {{ $centers->where('id', $key)->first()->name }}
    </h5>
    @php
        $totalMilk = $farmers->sum('milk');
        $totalAmount = $farmers->sum('grandtotal');
        $avgRate = $totalAmount / $totalMilk;
        
    @endphp
    <table class="w-100">
        <tr>
            <th>
                Average SNF: {{ truncate_decimals($farmers->avg('snf'), 2) }}
            </th>
            <th>
                Average Fat: {{ truncate_decimals($farmers->avg('fat'), 2) }}
            </th>
          
            <th>
                Average Total Rate / L : Rs. {{ truncate_decimals($avgRate, 2) }}
            </th>
        </tr>
        <tr>
            <th>
                Total Milk Collection : {{ $totalMilk }}
            </th>
            <th>
                Total Milk Amount : Rs. {{ $totalAmount }}
            </th>
            <th></th>
        </tr>

    </table>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    NO
                </th>
                <th>
                    Name
                </th>
                <th>
                    SNF
                </th>
                <th>
                    FAT
                </th>
                <th>
                    Rate / L
                </th>
                <th>
                    +TS <br> Rate / L
                </th>
                <th>
                    +CC <br> Rate / L
                </th>
                <th>
                    +Protsahan <br> Rate / L
                </th>
                <th>
                    +Transport <br> Rate / L
                </th>
                <th>
                    Total <br> Rate / L
                </th>
                <th>
                    Milk <br> Total
                </th>
                <th>
                    Amount
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($farmers as $farmer)
                <tr>
                    <th>
                        {{ $farmer->no }}
                    </th>
                    <td>{{ $farmer->name }}</td>
                    <td>{{ $farmer->snf }}</td>
                    <td>{{ $farmer->fat }}</td>
                    <td>{{ $farmer->rate }}</td>
                    <td>{{ $farmer->tc }}</td>
                    <td>{{ $farmer->cc }}</td>
                    <td>{{ $farmer->protsahan_rate }}</td>
                    <td>{{ $farmer->transport_rate }}</td>
                    <td>{{ $farmer->totalRate }}</td>
                    <td>{{ $farmer->milk }}</td>
                    <td>{{ $farmer->grandtotal }}</td>
                </tr>
            @endforeach

            </tr>
        </tbody>
    </table>
    <hr>
@endforeach
