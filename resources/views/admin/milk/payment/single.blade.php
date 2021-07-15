<tr id="payment-{{$payment->no}}" data-amount="{{(float)$payment->amount}}" data-id="{{$payment->id}}">
    <td>
        {{$payment->no}}
    </td>
    <td>
        {{$payment->name}}
    </td>
    <td>
        <input data-amount="{{(float)$payment->amount}}" data-id="{{$payment->id}}" onkeydown="amountEnter(this,event);" id="amount-{{$payment->id}}" type="number" min="1" class="form-control amount focus-select" value="{{(float)$payment->amount}}">
    </td>
    <td>
        <span class="btn btn-success" onclick="amountUpdate({{$payment->id}})">
            Update
        </span>
    </td>
</tr>
