<script>

    function initDayClose(){
        axios.get('{{route('pos.counter-current')}}')
        .then((res)=>{
            const status=res.data;
            $('#current-amount').html('Rs. '+status.current);
            $('#dayclose').modal('show');
        })
        .catch((err)=>{
            console.log(err);
        })
    }
    function  calculateClosingAmount() {

        $('#closing-amount').html('Rs. '+calculateClosingTotal());

    }

    function calculateClosingTotal(){
        const arr=['1000','500','100','50','20','10','5','2','1'];
        let  closingAmount=0;
        arr.forEach(id => {
            try {
                const amt= parseFloat($('#closing-amount-'+id).val());
                if(!isNaN(amt)){
                    closingAmount+=(amt * parseFloat(id));
                }
            } catch (error) {
                console.log();
            }
        });
        return closingAmount;
    }

    function closeCounter(){
        const total=calculateClosingTotal();
        showProgress("Closing Counter");
        axios.post('{{route('pos.counter-close')}}',{
            closing:total
        })
        .then((res)=>{
            if(res.data.status){
                window.location.href='{{route('pos.counter')}}';
            }
        })
        .catch((err)=>{
            console.log(err.response.data);
            alert(err.response.data);
            hideProgress();
        })
    }
</script>
