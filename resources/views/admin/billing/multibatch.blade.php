<style>
    .multi-batch-holder {
        position: fixed;
        top:0px;
        left:0px;
        right:0px;
        bottom:0px;
        padding:25px 50px;
        background: rgba(0, 0, 0, 0.3);
        z-index:9999;
        display: none;
    }
    .multi-batch-holder.active{
        display:block;
    }
    .multi-batch{
        height: 100%;
        background: white;
        padding:10px 20px;
    }
</style>
<div class="multi-batch-holder">
    <div class="multi-batch">
        <div class="row">
            <div class="col-md-4">
                <label for="connected_item">Item</label>
                <select name="connected_item" id="connected_item" class="form-control">

                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100" onclick="loadConnectedData()">Load Data</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-danger w-100" onclick="connectedCancel()">Cancel</button>
            </div>
        </div>
        <hr>
        <div id="connected_data">

        </div>
    </div>
</div>

@section('scripts4')
@php
    $connectedItems=\App\Models\ConnectedItem::get();
    $connectedLoadeditems=\App\Models\Item::whereIn('id',$connectedItems->pluck('item_id'))->get(['id','title']);
@endphp
   <script>
    

    
        const connectedItems={!! json_encode( $connectedItems)!!};
        const connectedLoadeditems={!! json_encode( $connectedLoadeditems)!!};
        function renderConnectedItem(o){
            const connectedLoadeditem=connectedLoadeditems.find(item=>item.id==o.item_id);
            return `<option value="${o.id}">${connectedLoadeditem.title}</option>`;
        }
        $(document).ready(function () {
            $('#connected_item').html(
                connectedItems.map(o=>renderConnectedItem(o)).join('')
            )
        });

        function connectedCancel(){
            $('#connected_data').html('');
            connectedHide();
        }

        function connected_item_category_changed(ele){
            const rate=$(ele).find(':selected').data('rate');
            console.log(rate,"rate");
            $('#connected_rate').val(rate);
        }

        function loadConnectedData(){
            axios.post('{{route('admin.billing.loadConnectedItem')}}',{id:$('#connected_item').val()})
            .then((res)=>{
                $('#connected_data').html(res.data);
            })
            .catch((err)=>{

            });
        }

        function connectedShow(){
                $('.multi-batch-holder').addClass('active');
        }
        function connectedHide(){
                $('.multi-batch-holder').removeClass('active');
        }

        function connected_addToBill(){
            const rate=parseFloat($('#connected_rate').val());
            if(rate<=0 || isNaN(rate)){
                alert('Please Enter Item Rate');
                return;
            }
            const qty=parseFloat($('#connected_qty').val());
            if(qty<=0 || isNaN(qty)){
                alert('Please Enter Qty');
                return;
            }

            const connected_item_id=$('#connected_item').val();
            const item_id=$('#item_id').val();
            const connectedLoadeditem=connectedLoadeditems.find(o=>o.id==item_id);

            const batch_id=parseInt( $('#connected_batch_id').val());
            const to_batch_id=parseInt( $('#connected_to_batch_id').val());
            const batch_no=$('#connected_batch_id').find(':selected').text()
            const to_batch_no=$('#connected_to_batch_id').find(':selected').text()
            
            console.log()
            if(batch_id==to_batch_id){
                alert('Same Batch Selected');
                return;
            }
            if(batch_id>to_batch_id){
                alert('start batch should be older than end batch');
                return;
            }

            const item_category_id=$('#connected_item_category_id').val();
            var billitem = {
                id: item_id,
                name: connectedLoadeditem.title,
                item_category_id: item_category_id==undefined?null:item_category_id,
                rate: rate,
                qty: qty,
                total: qty*rate,
                batch_id:batch_id,
                to_batch_id:to_batch_id,
                target_item_id:$('#target_item_id').val(),
                batch_type:'multi'
            };
            i += 1;
            const datastr = JSON.stringify(billitem)
            let  catname='';
            if($('#connected_item_category_id').length>0){
                catname='-' + $('#connected_item_category_id').find(':selected').text();
            }
            $catname= $('#connected_item_category_id').find(':selected').text();
            str = `<tr id='row-${i}'> <td><input class='billitems' type='hidden' name='billitems[]' value='${datastr}'/>  ${billitem.id} </td>
                <td> ${billitem.name} ${catname} <br> ${batch_no} - ${to_batch_no}</td>
                <td> ${billitem.rate} </td>
                <td> ${billitem.qty } </td>
                <td> ${billitem.total} </td>
                <td><span class='btn btn-danger btn-sm' onclick='removeProductItem(${i})'>Remove</span></td></tr>`;
                $('#billitemholder').append(str);
            $('#connected_data').html('');
            connectedHide();
            calculateAll();
        }
   </script>
@endsection