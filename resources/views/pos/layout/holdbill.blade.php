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
        overflow: auto;
        width:800px;
    }
    .hold-bills.active{
        right: 0px;
    }
    .topbar{
        position: sticky;
        top:0px;
        background: white;
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.3);

    }
    .holdbill{
        cursor: pointer;
    }
    #holdbill-search{
        outline: transparent;
        border-radius: 5px;
        border:1px solid #c5c5c5;
        font-size: 14px;
        padding: 5px 10px;
        width: 250px;
    }
</style>
<div class="hold-bills">
        <div class="d-flex justify-content-between p-2 topbar align-items-center">
            <strong>
                Hold Bills
            </strong>
            <span>
                <input id="holdbill-search" oninput="holdBillPanel.search(this.value)" type="search" placeholder="Search Customer Name">
                <button class="btn" onclick="holdBillPanel.refresh()">&#x21bb;</button>
                <button class="btn" onclick="holdBillPanel.hide()">&times;</button>
            </span>
        </div>
        <div class="data p-2">
            <table class="table table-hover">
                <tr>
                    <th>
                        HoldBill NO
                    </th>
                    <th>
                        Customer Name
                    </th>
                    <th>
                        Counter Name
                    </th>
                </tr>
                <tbody id="holdbill-list">

                </tbody>
            </table>
        </div>
</div>
<script>
    holdBillLock=false;
    var holdBillPanel={
        bills:null,
        search:function(_keyword){
            let html='';
            for (const key in this.bills) {
                if (Object.hasOwnProperty.call(this.bills, key)) {
                    const holdbill = this.bills[key];
                    if(holdbill.customer_name.toLowerCase().startsWith(_keyword.toLowerCase()) ){
                        html+="<tr class='holdbill' onclick='holdBillPanel.loadProcess("+holdbill.id+")'>";
                        html+="<td>"+holdbill.id+"</td>";
                        html+="<td>"+holdbill.customer_name+"</td>";
                        html+="<td>"+holdbill.name+"</td>";
                        html+="</tr>"
                    }
                    $('#holdbill-list').html(html);

                }
            }
        },
        loadData:function(){
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
        },
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
                    loadData();
                }
            }

        },

        show:function(){
            if(holdBillLock){
                return;
            }

            showProgress('Loading Holded Items');
            const obj=Object.assign({},billpanel.billitems);
            const data=JSON.stringify(obj);
            holdBillLock=true;
            axios.get('{{route('pos.billing.hold-list')}}')
            .then((res)=>{
                console.log(res.data);
                $('.hold-bills').addClass('active');
                let html='';
                // this.bills=res.data;
                holdBillLock=false;
                let _bills=[];
                for (let index = 0; index < res.data.length; index++) {
                    const holdbill = res.data[index];
                    html+="<tr class='holdbill' onclick='holdBillPanel.loadProcess("+holdbill.id+")'>";
                    html+="<td>"+holdbill.id+"</td>";
                    html+="<td>"+holdbill.customer_name+"</td>";
                    html+="<td>"+holdbill.name+"</td>";
                    html+="</tr>"
                    _bills['holdbill-'+holdbill.id.toString()]=holdbill;
                }
                this.bills=_bills;
                $('#holdbill-list').html(html);
                hideProgress();

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
        loadProcess:function(id){
            if(Object.keys( billpanel.billitems).length>0){
                if(confirm('There is Billing in process Do You Want Load Holded Bill??')){

                }else{
                    return;
                }
            }
            const _bill=this.bills['holdbill-'+id.toString()];
            let _billitems=[];
            const _data=JSON.parse(_bill.data);
            for (const key in _data) {
                if (Object.hasOwnProperty.call(_data, key)) {
                    const _billitem = _data[key];
                    _billitems[key]=_billitem;
                }
            }
            billpanel.resetBill();
            billpanel.billitems=_billitems;
            for (const key in billpanel.billitems) {
                billpanel.renderBillItem(key);
            }
            billpanel.calculateTotal();
            billpanel.holdBillId=id;
            this.hide();
        },
        hold:{

        }
    }
</script>
