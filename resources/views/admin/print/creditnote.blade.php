@extends('admin.print.app')
@section('content')
<div class="text-center b-700 f-16">
    {{ env('companyName') }}
</div>
<div class="text-center b-700 f-14">
    {{ env('companyAddress') }}
</div>
<div class="text-center b-700 f-12">
    {{ env('companyphone') }}
</div>

<div class="row">
    <div class="col-6 b-700 f-12 text-start">
       {{env('companyUseTax',false)?'VAT':'PAN'}} No: {{ env('companyVATPAN') }}
    </div>
    <div class="col-6 b-700 f-12 text-end">
        Reg No : {{ env('companyRegNO') }}
    </div>
    <div class="col-12">
        <div class="line-1"></div>
    </div>
    <div class=" col-12 text-center b-700 f-14">
        Credit Note
    </div>
    <div class="col-12 b-500 f-12 text-start">
        Credit Note No: {{ $note->id }}
    </div>
    <div class="col-6 b-500 f-12 text-start">
        Bill No: {{ $note->bill_no }}
    </div>
    <div class="col-6 b-500 f-12 text-end">
        Issued Date: {{_nepalidate( $note->date )}}
    </div>
    <div class="col-12 b-500 f-12 text-start">
        Customer's Name : {{ $note->customer_name }}
    </div>
    <div class="col-6 b-500 f-12 text-start">
        Customer's Phone : {{ $note->customer_phone }}
    </div>
    <div class="col-6 b-500 f-12 text-end">
        Customer's Vat/PAN : {{ $note->customer_pan }}
    </div>
</div>
<table class="print-table f-12 text-start">
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
        {{-- <th>
            Total
        </th> --}}
        <th>
            Discount
        </th>
        @if (env('companyUseTax',false))

        {{-- <th>
            Taxable
        </th> --}}
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
    @foreach ($note->noteItems as $item)
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
            {{-- <td>
                {{ (float) $item->amount }}
            </td> --}}
            <td>
                {{ (float) $item->discount }}
            </td>
            @if (env('companyUseTax',false))

            {{-- <td>
                {{ (float) $item->taxable }}
            </td> --}}
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
        $colspan=env('companyUseTax',false)?6:5;
    @endphp
    <tr class="no-border">
        <th colspan="{{$colspan}}" class="text-end">Total:</th>
        <td>Rs. {{ (float) $note->total }}</td>
    </tr>
    @if($note->discount>0 )

    <tr class="no-border">
        <th colspan="{{$colspan}}" class="text-end">Discount:</th>
        <td>Rs. {{ (float) $note->discount }}</td>
    </tr>
    @endif
    @if (env('companyUseTax', false))
        @if($note->discount>0 )
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-end">Taxable:</th>
            <td>Rs. {{ (float) $note->taxable }}</td>
        </tr>
        @endif
        <tr class="no-border">
            <th colspan="{{$colspan}}" class="text-end">Tax:</th>
            <td>Rs. {{ (float) $note->tax }}</td>
        </tr>
    @endif
    <tr class="no-border">
        <th colspan="{{$colspan}}" class="text-end">GrandTotal:</th>
        <td>Rs. {{ (float) $note->grandtotal }}</td>
    </tr>

    <tr class="no-border">
        <td colspan="{{$colspan+1}}">
            <div class="line-1"></div>
            <div class="f-12 b-700">
                {{numberTowords($note->grandtotal)}} Only|-
            </div>
            <div class="line-1"></div>
            <div class="f-12 b-700 text-center">
                Goods sold will be exchanged within seven days.
                <br>
                Thank you, Please Visit Us Again!!
            </div>
        </td>
    </tr>
</table>
@endsection
