<script>
    const expayCustomData = [];
    var expayHandle;

    function loadXpayment(){
        axios.post('')
    }


    function expayMethodChange(ele) {
        if (ele.value == 2) {
            $('#expay_bank_holder').show();
            $('#expay_custom_holder').hide();
        } else if (ele.value == 3) {
            $('#expay_bank_holder').hide();
            $('#expay_custom_holder').show();
        } else {
            $('#expay_bank_holder').hide();
            $('#expay_custom_holder').hide();
        }
    }



    function expayCustomBank(ele, id) {
        const amount = parseFloat(ele.value);

        if (isNaN(amount) || amount == 0) {
            if (exists('#expay_custom_bank_' + id)) {
                $('#expay_custom_bank_amount_' + id).remove();
                $('#expay_custom_bank_' + id).remove();
            }
        } else {
            if (!exists('#expay_custom_bank_' + id)) {
                const ele = `<input type="hidden" class="expay_custom_bank" name="xpay_custom_bank[]" id="expay_custom_bank_${id}" value="${id}">
                <input type="hidden" class="expay_custom_bank_amount" name="xpay_custom_bank_amount_${id}" id="expay_custom_bank_amount_${id}" value="${id}" value="${amount}">
                 `;
                $('#expay_custom_banks_holder').append(ele);
            } else {
                $('#expay_custom_bank_amount_' + id).val(amount).change();
            }
        }
    }


    function resetEXPayment() {
        $('#expay_amount').val(0);
        $('.expay_custom_input').val('').change();
        $('#expay_custom_banks_holder').html('');
        $('#expay_method').val(1).change();
        // expayMethodChange( $('#expay_method')[0]);
    }

    function expayHandleChange() {
        console.log(expayHandle.value);
        $('#expay_amount').val(expayHandle.value);
    }

    function addEXPayHandle() {
        if (exists('#expay')) {
            console.log('expay loaded');
            if (exists('.expay_handle')) {
                expayHandle = $('.expay_handle')[0];
                expayHandle.addEventListener('change', expayHandleChange, true);
                expayHandle.addEventListener('input', expayHandleChange, true);
            }

        }
    }

    function expayVerifyData() {
        if (exists('#expay')) {
            const method = $('#expay_method').val();
            const amount = $('#expay_amount').val();
            let totalamt = 0;
            const cashamt =parseFloat( $('#expay_custom_cash').val());
            if(!isNaN(cashamt)){
                totalamt+=cashamt;
            }

            if (method == 3) {
                $('.expay_custom_bank_amount').each(function (index, element) {
                    const localamt=parseFloat(element.value);
                    if(!isNaN(localamt)){
                        totalamt+=localamt;
                    }
                });
                if(amount==totalamt){
                    return true;
                }else{
                    alert('Amount not matching');
                    return false;
                }
            }

            return true;

        }
    }
    window.addEventListener('load', addXPayHandle, true);

    function loadXPay(data) {
        data.expay_amount = $('#expay_amount').val();
        data.expay_method = $('#expay_method').val();
        data.expay = $('#expay').val();
        data.expay_bank = $('#expay_bank').val();
        if (data.expay_method == 3) {
            data.expay_custom_bank = [];
            $('.expay_custom_bank').each(function(index, element) {
                const bank_id = $(element).val();
                data.expay_custom_bank.push(bank_id);
                data['expay_custom_bank_amount_' + bank_id] = $('#expay_custom_bank_amount_' + bank_id).val();
                data.expay_custom_cash = $('#expay_custom_cash').val();
            });
        }
        return data;
    }
</script>
