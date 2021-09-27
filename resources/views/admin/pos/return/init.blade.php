<div class="p-5">
    <input type="hidden" name="id" value="{{$bill->id}}">
    <div class="row">
        <div class="col-6">
            <label for="">Bill No</label> :
            <span>{{$bill->bill_no}}</span>
        </div>
        <div class="col-6 text-right">
            <label for="">Date</label> :
            <span>{{_nepalidate($bill->date)}}</span>
        </div>
        <div class="col-12">
            <hr>
        </div>
        <div class="col-6 b-500 f-12 text-start">
            <label for="">Purchaser's Name :</label>
            <input type="text" class="form-control" value=" {{ $bill->customer_name }}" name="customer_name" required>

        </div>
        <div class="col-6 b-500 f-12 text-right">
            <label for="">Purchaser's Address :</label> <input type="text" class="form-control" value=" {{ $bill->customer_address }}" name="customer_address">
        </div>
        <div class="col-6 b-500 f-12 text-start">
            <label for="">Purchaser's Phone :</label><input type="text" class="form-control" value=" {{ $bill->customer_phone }}" name="customer_phone">
        </div>
        <div class="col-6 b-500 f-12 text-right">
            <label for="">Purchaser's Vat :</label> <input type="text" class="form-control" value=" {{ $bill->customer_pan }}" name="customer_pan">
        </div>
    </div>
    <hr>
    <div class="text-right">
        <span class="btn btn-success" onclick="returnAll();">Return all</span>
    </div>
    <hr>
    <table class="table table-bordered f-12 text-start">
        <tr class="">
            <th>
                SN
            </th>
            <th>
                Item
            </th>
            <th>
                Rate
            </th>
            <th>
                Sell Qty
            </th>
            <th>
                Return Qty
            </th>
            <th>
                Total
            </th>
            <th>
                Discount
            </th>
            @if (env('companyUseTax',false))

            <th>
                Taxable
            </th>
            <th>
                Tax
            </th>
            @endif
            <th>
                Grand Total
            </th>
        </tr>
        @php
            $i = 1;
        @endphp
        @foreach ($bill->billItems as $item)
            <tr id="billitem-{{$item->id}}">
                <td>
                    {{ $i++ }}
                </td>
                <td>
                    {{ $item->name }}
                </td>
                <td>
                    {{ (float) $item->rate }}
                </td>
                <td>
                    {{$item->qty}}
                </td>
                <td>
                    <input type="hidden" name="bill_items[]" value="{{$item->id}}">
                    <input oninput="calculateTotal()" name="bill_item_{{$item->id}}" oninput="returnChangeQty(this)" min="0" max="{{$item->qty}}" data-billitem="{{$item->toJson()}}" type="number"  type="text" class="form-control return-qty" id="return-qty" value="0">
                </td>
                <td id="billitem-{{$item->id}}-amount">
                    0
                </td>
                <td id="billitem-{{$item->id}}-discount">
                   0
                </td>
                @if (env('companyUseTax',false))

                <td id="billitem-{{$item->id}}-taxable">
                    0
                </td>
                <td id="billitem-{{$item->id}}-tax">
                    0
                </td>
                @endif
                <td id="billitem-{{$item->id}}-total">
                    0
                </td>
            </tr>
        @endforeach
        {{-- @php
            $colspan=env('companyUseTax',false)?9:7;
        @endphp
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Total:</th>
            <td id="billitem-total-{{$item->id}}-total">{{ (float) $bill->total }}</td>
        </tr>
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Discount:</th>
            <td id="billitem-total-{{$item->id}}-discount">{{ (float) $bill->discount }}</td>
        </tr>
        @if (env('companyUseTax', false))

            <tr class="no-border" >
                <th colspan="{{$colspan}}" class="text-right">Taxable:</th>
                <td id="billitem-total-{{$item->id}}-taxable">{{ (float) $bill->taxable }}</td>
            </tr>
            <tr class="no-border" >
                <th colspan="{{$colspan}}" class="text-right">Tax:</th>
                <td id="billitem-total-{{$item->id}}-tax">{{ (float) $bill->tax }}</td>
            </tr>
        @endif
        @if ($bill->rounding>0)
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Rounding:</th>
            <td id="billitem-total-{{$item->id}}-rounding">{{ (float) $bill->rounding }}</td>
        </tr>
        @endif
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">GrandTotal:</th>
            <td id="billitem-total-{{$item->id}}-grandtotal">{{ (float) $bill->grandtotal }}</td>
        </tr>
        @php
            $paid=$bill->paid-$bill->return;
        @endphp
        @if ($paid > 0)
            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-right">Paid:</th>
                <td id="billitem-total-{{$item->id}}-paid">{{ (float) $paid }}</td>
            </tr>
        @endif
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Due:</th>
            <td id="billitem-total-{{$item->id}}-due">{{ (float) $bill->due }}</td>
        </tr>

 --}}

    </table>
    <div class="text-right py-2">
        <span style="padding: 8px;">
            <input type="radio" id="print-directly" > Print Directly
        </span>
        <button class="btn btn-primary">Generate Sales Return</button>
    </div>
</div>
