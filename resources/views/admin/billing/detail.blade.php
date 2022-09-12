<div class="p-5">
    <div class="row">
        <div class="col-md-3">
            <label for="">Bill No</label> :
            <span>{{$bill->id}}</span>
        </div>
        <div class="col-md-3">
            <label for="">Date</label> :
            <span>{{_nepalidate($bill->date)}}</span>
        </div>
        
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Particular</th>
            <th>Rate</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        @foreach ($bill->billitems as $item)
            <tr>
                <td>
                    {{$item->name}}
                </td>
                <td>
                    {{$item->rate}}
                </td>
                <td>
                    {{$item->qty}}
                </td>
                <td>
                    {{$item->total}}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-right pr-1">Gross Total</td>
            <td>{{$bill->grandtotal}}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right pr-1">Discount</td>
            <td>{{$bill->dis}}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right pr-1">Net Total</td>
            <td>{{$bill->net_total}}</td>
        </tr>
        <tr>
            <td colspan="3" class="text-right pr-1">Paid</td>
            <td>{{$bill->paid}}</td>
        </tr>
        @if ($bill->due>0)
            
            <tr>
                <td colspan="3" class="text-right pr-1">Due</td>
                <td>{{$bill->due}}</td>
            </tr>
        @endif
        @if ($bill->return>0)
            
            <tr>
                <td colspan="3" class="text-right pr-1">Return</td>
                <td>{{$bill->return}}</td>
            </tr>
        @endif
    </table>
    @if (count($ledgers)>0)
    <hr>
    <table>
        <tr>
            <th>
                Payment Method
            </th>
            <th>
                Amount
            </th>
        </tr>
        @foreach ($ledgers as $ledger)
            <tr>
                <th>
                    {{$ledger->name}}
                </th>
                <td>
                    {{$ledger->amount}}
                </td>
            </tr>

        @endforeach
    </table>
    @endif

    <div>
        <a href="{{route('restaurant.print',['id'=>$bill->id])}}" class="btn btn-primary" target="_newblank"> Print</a>
    </div>
</div>
