@php
    $t=0;$bt=0;$pt=0;

@endphp


<div class="card py-4 px-2 my-3 shadow" style="font-size:2rem;font-weight:600;">
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
            <input  placeholder="Enter Payment Amount" type="number" id="amount" class="form-control" min="1" step="=0.01" >
        </div>
        <div class="col-md-12 py-2">
            <label>
                Payment Method
            </label>
            <textarea id="method" class="form-control"></textarea>
        </div>
        <div class="col-md-4">
            <button class="btn btn-success" onclick="pay()">Pay</button>
        </div>

    </div>
</div>
