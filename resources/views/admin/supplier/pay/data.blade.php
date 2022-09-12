@php
    $t=0;$bt=0;$pt=0;

@endphp


<div class="card py-4 px-2  shadow" style="">
    <table class="table table-bordered">
        <tr>
            <th>
                Date
            </th>
            <th>
                Amount (Rs.)
            </th>
            <th></th>
        </tr>
        @foreach ($payments as $payment)
            <tr>
                <td>
                    {{_nepalidate($payment->date)}}
                </td>
                <td>
                    {{$payment->amount}}
                </td>

                <td>
                    @if (auth_has_per('07.11'))
                    <button class="btn btn-danger" onclick="delPayment({{$payment->foreign_key}},{{$payment->id}})">
                        Delete
                    </button>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    <strong>
        Current Balance :
    </strong>
    @if ($supplier->balance==0)
        0
    @else
    {{rupee((float)($supplier->balance>0? $supplier->balance:(-1*$supplier->balance)))}}
    {{$supplier->balance<0?"CR":"DR" }}
    @endif
</div>

<div class="card py-4 px-2 my-3 shadow">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <input readonly type="text" name="date" id="nepali-datepicker" class="form-control next" data-next="user_id" placeholder="Date">
            </div>
        </div>
        <div class="col-md-6">
            {{-- <input type="hidden" id="u_id" value="{{$id}}"> --}}
            <input  placeholder="Enter Payment Amount" type="number" id="amount" class="form-control xpay_handle" min="1" step="=0.01" >
        </div>
        <div class="col-md-12 py-2">
            <label>
                Payment Method
            </label>
            <textarea id="method" class="form-control"></textarea>
        </div>
        <div class="col-12">
            <div class="row">
                @include('admin.payment.take',['xpay_type'=>2])
                <div class="col-md-3">
                    <button class="btn btn-success" onclick="pay()">Add Payment</button>
                </div>
            </div>
        </div>

    </div>
</div>
