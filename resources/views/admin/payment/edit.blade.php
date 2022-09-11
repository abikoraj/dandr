<div id="expay_custom_holder" >
    <input type="hidden" name="expay_method" id="expay_method" >
    <table class="table">
        <tr>
            <th>Acc</th>
            <th>Amount</th>
            
        </tr>
        @foreach ($ledgers as $ledger)
            <tr>
                <input type="hidden" name="xpay_ledger_ids[]" value="{{$ledger->id}}">
                <th>
                    {{$ledger->name}}
                </th>
                <th>
                    <input type="number" class="expay_ledger_amount" name="xpay_ledger_{{$ledger->id}}" id="xpay_ledger_{{$ledger->id}}" value="{{$ledger->amount}}">
                </th>
            </tr>

        @endforeach
        

</div>
