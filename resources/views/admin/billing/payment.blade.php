<style>
    .payment-holder {
        position: fixed;
        top: 0px;
        bottom: 0px;
        left: 0px;
        right: 0px;
        background: rgba(0, 0, 0, 0.2);
        z-index: 999;
        padding: 50px 100px;
        display: none;
    }

    .payment-holder.active {
        display: block;
    }

    .payment-wrapper {
        background: white;
        width: 100%;
        height: 100%;

    }

    .xpay-selector {
        padding: 10px;
        border: 1px solid gray;
    }

    .xpay-selector.selected {
        border: 1px solid #007ACC;
        background: #007ACC;
        color: white;
    }

    .xpay-holder {
        display: none;
    }

    .xpay-holder.selected {
        display: block;
    }
</style>
<div class="payment-holder" id="xpayment_holder">

    <div class="payment-wrapper">
        <input type="hidden" id="xpay" name="xpay" value="{{ $xpay_type ?? 1 }}">
        <input type="hidden" id="xpay_amount" name="xpay_amount" value="0">
        <input type="hidden" id="xpay_method" value="1">
        <div class="row m-0 h-100">
            <div class="col-md-3 p-0 h-100" style="border-right:1px solid gray;">
                <div class="xpay-selectors">
                    <div class="xpay-selector xpay-selector-1 selected" onclick="xpayselector(1)">
                        Cash (ALT + 1)
                    </div>
                    <div class="xpay-selector xpay-selector-2" onclick="xpayselector(2)">
                        Bank (ALT + 2)
                    </div>
                    <div class="xpay-selector xpay-selector-3" onclick="xpayselector(3)">
                        Mixed (ALT + 3)
                    </div>

                </div>
            </div>
            @php
                $xpay_banks = getBanks();
            @endphp
            <div class="col-9 p-3">
                <div class="xpay-holder xpay-holder-2 ">
                    <select name="xpay_bank" id="xpay_bank" class="form-control ms">
                        @foreach ($xpay_banks as $xpay_bank)
                            <option value="{{ $xpay_bank->account_id }}">{{ $xpay_bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xpay-holder xpay-holder-3 ">
                    <table>
                        <tr>
                            <th>
                                Cash
                            </th>
                            <td>
                                <input type="number" class="xpay_mixed_input" name="account_cash" id="account_cash" min="0">
                            </td>
                        </tr>
                        @foreach ($xpay_banks as $xpay_bank)
                            <tr>

                                <th>
                                    {{ $xpay_bank->name }}
                                </th>
                                <td>
                                    <input type="number" min="0" step="0.01" data-id="{{$xpay_bank->account_id }}"
                                        id="account_bank_{{ $xpay_bank->account_id }}" class="xpay_mixed_input xpay_mixed_input_bank" min="0">
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div style="position: absolute;bottom:0px;padding:10px;">
                    <button class="btn btn-primary" onclick="savePayment()">
                        Save Bill (ALT + S)
                    </button>
                    <button class="btn btn-danger" onclick="closePayment()">
                        Cancel (ALT + C)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts2')
    <script>
        const banks = {!! json_encode($xpay_banks) !!}
        var paymentOBJ=null;
        function xpayselector(method) {
            console.log(method);
            $('#xpay_method').val(method);
            $('.xpay-selector').removeClass('selected');
            $('.xpay-selector-' + method).addClass('selected');
            $('.xpay-holder').removeClass('selected');
            $('.xpay-holder-' + method).addClass('selected');
        }
        $('input, body,.form-control1, .form-control').bind('keydown', 'alt+1', function(e) {
            xpayselector(1);
        });
        $('input, body,.form-control1, .form-control').bind('keydown', 'alt+c', function(e) {
            closePayment();
        });
        $('input, body,.form-control1, .form-control').bind('keydown', 'alt+s', function(e) {
            savePayment();
        });
        $('input, body,.form-control1, .form-control').bind('keydown', 'alt+2', function(e) {
            xpayselector(2);
            $('#xpay_bank').focus();
        });
        $('input, body,.form-control1, .form-control').bind('keydown', 'alt+3', function(e) {
            xpayselector(3);
            $('#account_cash').focus();
        });

        function checkBalance() {
            let cash = parseFloat($('#xpay_amount').val());
            if (isNaN(cash)) {
                cash = 0;
            }
            let amount=0;
            $('.xpay_mixed_input').each(function (index, element) {
                const localAmt = parseFloat(element.value);
                if (!isNaN(localAmt)) {
                    amount+=localAmt;
                }
            });
            if(amount!=cash){
                alert();
                return false;
            }
            return true;
        }


        function savePayment(){
            paymentOBJ={
                xpay:1,
                xpay_amount:parseFloat($('#xpay_amount').val()),
                xpay_method:parseInt($('#xpay_method').val()),
                xpay_bank:parseInt($('#xpay_bank').val()),
                xpay_custom_bank:[],
                xpay_custom_cash:parseFloat($('#account_cash').val()),
            };
            console.log(paymentOBJ);

            
            if(paymentOBJ.xpay_method==3){      
                if(!checkBalance()){
                    return;
                }
                $('.xpay_mixed_input_bank').each(function (index, element) {
                    const localAmt = parseFloat(element.value);
                    if (!isNaN(localAmt)) {
                        if(localAmt>0){ 
                            const id=parseInt(element.dataset.id);
                            paymentOBJ.xpay_custom_bank.push(id),
                            paymentOBJ['xpay_custom_bank_amount_'+id]=localAmt;
                        }
                    }
                });
            }

            resetPayment();
            closePayment();
            save();
        }
        function openPayment() {
            $('#xpayment_holder').addClass('active');
        }

        function closePayment() {
            $('#xpayment_holder').removeClass('active');
        }

        function resetPayment() {
            xpayselector(1);
            $('#xpay_bank').val(null);
            $('.xpay_mixed_input').val(null);
        }
    </script>
@endsection
