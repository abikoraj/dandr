@foreach ($datas as $key=>$farmers)
<h5>
    Center :  {{$centers->where('id',$key)->first()->name}}
</h5>
@php
    $totalMilk=$farmers->sum('milk');
    $totalAmount=$farmers->sum('grandtotal');
    $avg=$totalAmount/$totalMilk;
    $snf=0;
    $fat=0;
    $rate=0;
    $totalRate=0;
    $total=0;
    $grandtotal=0;
    $milk=0;
    $count=0;
@endphp
<table class="w-100">
    <tr>
        <th>
            Average SNF: {{ truncate_decimals( $farmers->avg('snf'),2)}}
        </th>
        <th>
            Average Fat: {{ truncate_decimals( $farmers->avg('fat'),2)}}
        </th>
        <th>
            Average Rate / L :  Rs. {{ truncate_decimals( $farmers->avg('rate'),2)}}
        </th>
        <th>
            Average Total Rate / L :  Rs. {{ truncate_decimals( $farmers->avg('totalRate'),2)}}
        </th>
    </tr>
    <tr>
        <th>
            Average Total Rate / L :  Rs. {{ truncate_decimals( $avg,2)}}
        </th>
        <th>
            Total Milk Collection : {{$totalMilk}}
        </th>
        <th>
            Total Milk Amount : Rs. {{$totalAmount}}
        </th>
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
        @php
            $snf+=$farmer->snf;
            $fat+=$farmer->fat;
            $rate+=$farmer->rate;
            $totalRate+=$farmer->totalRate;
            $total+=$farmer->total;
            $grandtotal+=$farmer->grandtotal;
            $milk+=$farmer->milk;
            $count++;
        @endphp
        {{-- <tr>
            <th>
                {{$farmer->no}}
            </th>
            <td>{{$farmer->name}}</td>
            <td>{{$farmer->snf}}</td>
            <td>{{$farmer->fat}}</td>
            <td>{{$farmer->rate}}</td>
            <td>{{$farmer->tc}}</td>
            <td>{{$farmer->cc}}</td>
            <td>{{$farmer->protsahan_rate}}</td>
            <td>{{$farmer->transport_rate}}</td>
            <td>{{$farmer->totalRate}}</td>
            <td>{{$farmer->milk}}</td>
            <td>{{$farmer->grandtotal}}</td>
        </tr> --}}
            
        @endforeach
        <tr>
            <th>--</th>
            <th>--</th>
            <th>
                {{$snf/$count}}
            </th>
            <th>
                {{$fat/$count}}
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                {{$rate/$count}}
            </th>
            <th>
                {{$totalRate/$count}}
            </th>
            {{-- <th>
                {{$total}}
            </th> --}}
            <th>
                {{$milk}}
            </th>
            <th>
                {{$grandtotal}}
            </th>
            {{-- <th>
                {{$count}}
            </th> --}}
        </tr>
    </tbody>
</table>
    <hr>
@endforeach