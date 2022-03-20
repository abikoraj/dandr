<tr data-name="{{$bill->customer_name}}" data-billno="{{$bill->billno}}">
    <td>
        {{$bill->bill_no}}
    </td>
    <td>
        {{_nepalidate($bill->date)}}
    </td>
    <td>
        {{$bill->customer_name}}
    </td>
    <td>
        {{(float)$bill->grandtotal}}
    </td>

    <td>
        <span class="btn btn-primary" onclick="loadDetail({{$bill->id}},'{{$bill->bill_no}}');">
            View Detail
        </span>
        @if ($print==1)
        <span class="btn btn-primary" onclick="initPrint({{$bill->id}},'{{$bill->bill_no}}');">
            print
        </span>
        @endif
        @if ($cancel==1)
        <span class="btn btn-primary" onclick="cancel({{$bill->id}},'{{$bill->bill_no}}');">
            Cancel
        </span>
        @endif
        @if ($return==1)
        <a class="btn btn-primary" target="_blank" href="{{route('admin.pos.billing.return-single',['bill'=>$bill->id])}}">
            Return
        </a>
        @endif
    </td>
</tr>
