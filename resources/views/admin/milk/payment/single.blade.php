<tr id="payment-{{$payment->id}}" >
    <td>
        {{$payment->no}}
    </td>
    <td>
        {{_nepalidate($payment->date)}}
    </td>
    <td>
        {{$payment->name}}
    </td>
    <td>
        {{(float)$payment->amount}}
    </td>
    <td>
        <span class="btn btn-success btn-sm" onclick="initUpdate('{{route('admin.farmer.milk.payment.update',['id'=>$payment->id])}}')">
            Update
        </span>
        <span class="btn btn-danger btn-sm" onclick="amountDelete({{$payment->id}})">
            Delete
        </span>
    </td>
</tr>
