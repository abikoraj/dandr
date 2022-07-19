
@foreach ($bills as $bill)
    <tr id="bill-{{$bill->id}}" class="{{$bill->is_canceled==1?'canceled':''}}">
        <th>
            {{$bill->id}}
        </th>
        <th>
            {{_nepalidate($bill->date)}}
        </th>
        <td>
            {{$bill->name}}

        </td>
        <td>
            {{$bill->billitems}}
        </td>
        <td>
            {{$bill->grandtotal}}
        </td>
        <td>
            <button data-href="{{route('admin.billing.detail',['id'=>$bill->id])}}" onclick="win.showGet('{{$bill->id}}',this.dataset.href)">Details</button>
            
            <button data-href="{{route('admin.billing.del',['id'=>$bill->id])}}" onclick="" Class>Cancel</button>
        </td>
    </tr>
@endforeach