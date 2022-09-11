@if (hasPay())
    <input type="hidden"  id="xpay" name="xpay" value="{{$xpay_type??1}}">
    <input type="hidden" id="xpay_amount" name="xpay_amount" value="0">
    <div class="{{$xclass??"col-md-3"}}">
        @if ($showlabel??false)
            <label for="xpay_method">Payment Method</label>
        @endif
            <select name="xpay_method" id="xpay_method" onchange="xpayMethodChange(this)"  class="form-control ms">
                <option value="1">Cash</option>
                <option value="2">Bank</option>
                <option value="3">Mixed</option>
            </select>
    </div>
    @php
        $xpay_banks=getBanks();
    @endphp
    <div id="xpay_bank_holder" class="{{$xclass??"col-md-3"}}" style="display: none;">
        @if ($showlabel??false)
            <label for="xpay_bank">Bank</label>
        @endif
        <select name="xpay_bank" id="xpay_bank" class="form-control ms">
            @foreach ($xpay_banks   as $xpay_bank)
                <option value="{{$xpay_bank->account_id}}">{{$xpay_bank->name}}</option>
            @endforeach
        </select>

    </div>
    <div id="xpay_custom_holder" class="{{$xclassLarge??"col-md-6"}}" style="display: none;">
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
                    class="xpay_custom_input"
                    step="0.01" name="xpay_custom_cash" id="xpay_custom_cash">
                </td>
            </tr>
            @foreach ($xpay_banks  as $xpay_bank)
                <tr>
                    <th>
                        {{$xpay_bank->name}}
                    </th>
                    <td>
                        <input type="number"
                        min="0"
                        step="0.01"
                        class="xpay_custom_input"
                        onchange="xpayCustomBank(this,{{$xpay_bank->account_id}})"
                        oninput="xpayCustomBank(this,{{$xpay_bank->account_id}})">
                    </td>
                </tr>
                @endforeach
            </table>
            <div id="xpay_custom_banks_holder">

            </div>

    </div>

@endif




