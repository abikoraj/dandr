<hr>
<form action="{{route('admin.farmer.passbook.data')}}" onsubmit="return closeSession(event,this);">
    @csrf
    <div class="row">
        <input type="hidden" name="year" value="{{ $farmer->session[0] }}">
        <input type="hidden" name="month" value="{{ $farmer->session[1] }}">
        <input type="hidden" name="session" value="{{ $farmer->session[2] }}">
        <input type="hidden" name="center_id" value="{{ $farmer->center_id }}">
        <input type="hidden" name="id" value="{{ $farmer->id }}">
        <div class="col-md-3">
            {{-- <label for="date">Date</label> --}}
            <input type="text" name="date" class="form-control" id="closedate"
                value="{{ _nepalidate($closingDate) }}" readonly required>
        </div>

        <div class="col-md-3">
            <input type="number" name="payment_amount" id="amount" placeholder="Payment Amount"
                class="form-control xpay_handle" min="1" required value="{{ makeFive($farmer->nettotal) }}">
        </div>
        @if (!$farmer->old)
            <div class="col-md-3">
                <input type="hidden" name="snf" value="{{ $farmer->snfavg }}">
                <input type="hidden" name="fat" value="{{ $farmer->fatavg }}">
                <input type="hidden" name="rate" value=" {{ $farmer->milkrate }}">
                <input type="hidden" name="milk" value="{{ $farmer->milkamount }}">
                <input type="hidden" name="total" value=" {{ $farmer->total }}">
                <input type="hidden" name="grandtotal" value=" {{ $farmer->grandtotal }}">
                <input type="hidden" name="cc" value=" {{ $farmer->cc }}">
                <input type="hidden" name="tc" value=" {{ $farmer->tc }}">
                <input type="hidden" name="due" value=" {{ $farmer->purchase }}">
                <input type="hidden" name="bonus" value=" {{ $farmer->bonus }}">
                <input type="hidden" name="advance" value=" {{ $farmer->advance }}">
                <input type="hidden" name="prevdue" value=" {{ $farmer->prevdue }}">
                <input type="hidden" name="nettotal" value=" {{ $farmer->nettotal }}">
                <input type="hidden" name="balance" value=" {{ $farmer->balance }}">
                <input type="hidden" name="prevbalance" value=" {{ $farmer->prevbalance }}">
                <input type="hidden" name="paidamount" value=" {{ $farmer->paidamount }}">
                <input type="hidden" name="fpaid" value=" {{ $farmer->fpaid }}">
                <input type="checkbox" name="close" id="close" value="1" checked> Close Session
            </div>
        @else
        <input type="checkbox" name="passbookchecked" id="passbookchecked" value="1" checked> Passbook Checked

        @endif
        <div class="col-md-3 ">
            <button class="btn btn-success" >Save Payment</button>
        </div>
        @include('admin.payment.take', ['xpay_type' => 2])
    </div>
</form>
