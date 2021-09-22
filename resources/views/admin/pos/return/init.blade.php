<div class="p-5">
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
            <label for="">Purchaser's Name :</label> {{ $bill->customer_name }}
        </div>
        <div class="col-6 b-500 f-12 text-right">
            <label for="">Purchaser's Address :</label> {{ $bill->customer_address }}
        </div>
        <div class="col-6 b-500 f-12 text-start">
            <label for="">Purchaser's Phone :</label> {{ $bill->customer_phone }}
        </div>
        <div class="col-6 b-500 f-12 text-right">
            <label for="">Purchaser's Vat :</label> {{ $bill->customer_pan }}
        </div>
    </div>
    <table class="table table-bordered f-12 text-start">
        <tr class="">
            <th>
                SN
            </th>
            <th>
                Item
            </th>
            <th>
                Qty
            </th>
            <th>
                Return Qty
            </th>
            <th>
                Rate
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
                    {{$item->qty}}
                </td>
                <td>
                    <input oninput="returnChangeQty(this)" min="0" max="{{$item->qty}}" data-billitem="{{$item->toJson()}}" type="number"  type="text" class="form-control return-qty" id="return-qty" value="0">
                </td>
                <td>
                    {{ (float) $item->rate }}
                </td>
                <td id="billitem-{{$item->id}}-amount">
                    {{ (float) $item->amount }}
                </td>
                <td id="billitem-{{$item->id}}-discount">
                    {{ (float) $item->discount }}
                </td>
                @if (env('companyUseTax',false))

                <td id="billitem-{{$item->id}}-taxable">
                    {{ (float) $item->taxable }}
                </td>
                <td id="billitem-{{$item->id}}-tax">
                    {{ (float) $item->tax }}
                </td>
                @endif
                <td id="billitem-{{$item->id}}-total">
                    {{ (float) $item->total }}
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
</div>
