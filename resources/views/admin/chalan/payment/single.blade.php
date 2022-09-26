<tr id="payment-{{$payment->id}}">
    <th>
        {{$payment->name}}
    </th>
    <th>
        {{$payment->amount}}
    </th>
    <th>
        <button class="btn btn-danger" onclick="delPayment({{$payment->id}})">Del</button>
    </th>
</tr>
