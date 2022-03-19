<tr id="payment-{{$payment->id}}" data-amount="{{(float)$payment->amount}}" data-id="{{$payment->id}}">
    <td>
        {{$payment->no}}
    </td>
    <td>
        <input data-date="{{$payment->date}}" data-id="{{$payment->id}}"  id="date-{{$payment->id}}" type="text"  class="form-control focus-select payment-date" value="{{_nepalidate($payment->date)}}">

    </td>
    <td>
        {{$payment->name}}
    </td>
    <td>
        <input data-amount="{{(float)$payment->amount}}" data-id="{{$payment->id}}" onkeydown="amountEnter(this,event);" id="amount-{{$payment->id}}" type="number" min="1" class="form-control amount focus-select" value="{{(float)$payment->amount}}">
    </td>
    <td>
        <span class="btn btn-success btn-sm" onclick="amountUpdate({{$payment->id}})">
            Update
        </span> 
        <span class="btn btn-danger btn-sm" onclick="amountDelete({{$payment->id}})">
            Delete
        </span>
    </td>
</tr>
