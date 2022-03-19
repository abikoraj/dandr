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
            <tr>
                <td>
                    {{ $i++ }}
                </td>
                <td>
                    {{ $item->name }}
                </td>
                <td>
                    {{ (float) $item->qty }}
                </td>
                <td>
                    {{ (float) $item->rate }}
                </td>
                <td>
                    {{ (float) $item->amount }}
                </td>
                <td>
                    {{ (float) $item->discount }}
                </td>
                @if (env('companyUseTax',false))

                <td>
                    {{ (float) $item->taxable }}
                </td>
                <td>
                    {{ (float) $item->tax }}
                </td>
                @endif
                <td>
                    {{ (float) $item->total }}
                </td>
            </tr>
        @endforeach
        @php
            $colspan=env('companyUseTax',false)?8:6;
        @endphp
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Total:</th>
            <td>{{ (float) $bill->total }}</td>
        </tr>
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Discount:</th>
            <td>{{ (float) $bill->discount }}</td>
        </tr>
        @if (env('companyUseTax', false))

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-right">Taxable:</th>
                <td>{{ (float) $bill->taxable }}</td>
            </tr>
            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-right">Tax:</th>
                <td>{{ (float) $bill->tax }}</td>
            </tr>
        @endif
        @if ($bill->rounding>0)
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Rounding:</th>
            <td>{{ (float) $bill->rounding }}</td>
        </tr>
        @endif
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">GrandTotal:</th>
            <td>{{ (float) $bill->grandtotal }}</td>
        </tr>
        @if ($bill->paid > 0)

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-right">Paid:</th>
                <td>{{ (float) $bill->paid }}</td>
            </tr>
        @endif
        @if ($bill->due > 0)

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-right">Due:</th>
                <td>{{ (float) $bill->due }}</td>
            </tr>
        @endif
        @if ($bill->return > 0)

            <tr class="no-border">
                <th colspan="{{$colspan}}" class="text-right">Return:</th>
                <td>{{ (float) $bill->return }}</td>
            </tr>
        @endif
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Counter:</th>
            <td>{{  $bill->counter_name }}</td>
        </tr>
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-right">Printed Copy:</th>
            <td>{{  $bill->copy }}</td>
        </tr>

    </table>
</div>
