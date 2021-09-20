@section('holdbtn')
<span class="link" onclick="holdBillPanel.show()">Hold List</span>
@endsection
<style>
    .hold-bills{
        z-index: 10;
        position: fixed;
        top:0;right:-800px;
        bottom:0px;
        width: 0px;
        background: white;
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.3);
        transition: 0.5s all;
        padding:10px;
        overflow: hidden;
        width:800px;
    }
    .hold-bills.active{
        right: 0px;
    }
</style>
<div class="hold-bills">
        <div class="text-end"><button class="btn" onclick="holdBillPanel.hide()">&times;</button></div>
</div>
<script>
    holdBillLock=false;
    var holdBillPanel={
        init:function(){
            if(holdBillLock){
                return;
            }
            if(Object.keys( billpanel.billitems).length==0){
                alert('No Items Added To Bills');
                return;
            }else{
                let customer_name=prompt('Please Enter Customer Name or Phone number');
                if(customer_name==null || customer_name==''){
                    alert('bill Holding Canceled By User');
                }else{
                    showProgress('Saving Bill Hold');
                    const obj=Object.assign({},billpanel.billitems);
                    const data=JSON.stringify(obj);
                    holdBillLock=true;
                    axios.post('{{route('pos.billing.hold')}}',{"customer_name":customer_name,"data":data})
                    .then((res)=>{
                        hideProgress();
                        billpanel.resetBill();
                        holdBillLock=false;

                    })
                    .catch((err)=>{
                        hideProgress();
                        $.notify("Cannot Hold Bill Currently, Please Try Again", {
                            className: "error",
                        });
                        holdBillLock=false;

                    });
                }
            }

        },
        show:function(){
            if(holdBillLock){
                return;
            }

            showProgress('Saving Bill Hold');
            const obj=Object.assign({},billpanel.billitems);
            const data=JSON.stringify(obj);
            holdBillLock=true;
            axios.post('{{route('pos.billing.hold')}}',{"customer_name":customer_name,"data":data})
            .then((res)=>{
                console.log(res.data);
                $('.hold-bills').addClass('active');
            })
            .catch((err)=>{
                hideProgress();
                $.notify("Cannot Hold Bill Currently, Please Try Again", {
                    className: "error",
                });
                holdBillLock=false;

            });
        },
        hide:function(){
            $('.hold-bills').removeClass('active');

        },
        hold:{

        }
    }
</script>
