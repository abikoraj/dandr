<script>
    const xpayCustomData=[];
    var xpayHandle;
    function xpayMethodChange(ele) {
        if(ele.value==2){
            $('#xpay_bank_holder').show();
            $('#xpay_custom_holder').hide();
        }else if(ele.value==3){
            $('#xpay_bank_holder').hide();
            $('#xpay_custom_holder').show();
        }else{
            $('#xpay_bank_holder').hide();
            $('#xpay_custom_holder').hide();
        }
    }

    function addXpayData(){

    }

    function xpayCustomBank(ele,id){
        const amount=parseFloat(ele.value);

        if(isNaN(amount) || amount==0){
            if(exists('#xpay_custom_bank_'+id)){
                $('#xpay_custom_bank_amount_'+id).remove();
                $('#xpay_custom_bank_'+id).remove();
            }
        }else{
            if(!exists('#xpay_custom_bank_'+id)){
                const ele=`<input type="hidden" name="xpay_custom_bank[]" id="xpay_custom_bank_${id}" value="${id}">
                <input type="hidden" name="xpay_custom_bank_amount_${id}" id="xpay_custom_bank_amount_${id}" value="${id}" value="${amount}">
                 `;
                 $('#xpay_custom_banks_holder').append(ele);
            }else{
                $('#xpay_custom_bank_amount_'+id).val(amount).change();
            }
        }
    }


    function resetXPayment(){
        $('#xpay_amount').val(0);
        $('.xpay_custom_input').val('').change();
        $('#xpay_custom_banks_holder').html('');
        $('#xpay_method').val(1).change();
        // xpayMethodChange( $('#xpay_method')[0]);
    }

    function xpayHandleChange() {
        console.log(xpayHandle.value);
        $('#xpay_amount').val(xpayHandle.value);
    }

    window.addEventListener('load', function(){
        if(exists('#xpay')){
            console.log('xpay loaded');

            if(exists('.xpay_handle')){
                xpayHandle=$('.xpay_handle')[0];
                xpayHandle.addEventListener('change',xpayHandleChange,true);
                xpayHandle.addEventListener('input',xpayHandleChange,true);
            }
        }
    }, true);

</script>
