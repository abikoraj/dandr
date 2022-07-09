<div id="expay_custom_holder" >
    <input type="hidden" name="expay_method" id="expay_method" value="{{$method}}">
    <table class="table">
        <tr>
            <th>Acc</th>
            <th>Amount</th>
        </tr>
        <tr>
            <th>
                Cash
            </th>
            <td>
                <input type="number" min="0"
                class="expay_custom_input"
                step="0.01" name="expay_custom_cash" id="expay_custom_cash" value="{{$detail->c}}" required>
            </td>
        </tr>
        @foreach ($banks  as $bank)
            <tr>
                <th>
                    {{$bank['detail']->name}}
                </th>
                <td>
                    <input type="hidden" name="expay_custom_bank[]" value="{{$bank['detail']->id}}">
                    <input type="number"
                    min="0"
                    step="0.01"
                    class="expay_custom_bank_amount"
                    id="expay_custom_bank_amount_{{$bank['detail']->id}}"
                    name="expay_custom_bank_amount_{{$bank['detail']->id}}"
                    value="{{$bank['amount']}}"
                    required
                   >
                </td>
            </tr>
            @endforeach
        </table>
        <div id="xpay_custom_banks_holder">

        </div>

</div>
