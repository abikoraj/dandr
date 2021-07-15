<div class="modal fade" id="sellitem_change" tabindex="-1" role="dialog" aria-labelledby="sellitem_changemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Ledger - Distrinutor Sell Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="s_title">

            </div>
            <hr>
            <div class="row">
                <input type="hidden"  id="id">
                <div class="col-md-3">
                    <label for="s_rate">Rate</label>
                    <input type="number" name="s_rate" id="s_rate" class="form-control" step="0.01" oninput="s_calculate();">
                </div>
                <div class="col-md-3">
                    <label for="s_qty">Qty</label>
                    <input type="number" name="s_qty" id="s_qty" class="form-control" step="0.01" oninput="s_calculate();">
                </div>
                <div class="col-md-6">
                    <label for="s_total">Total</label>
                    <input type="number" name="s_amount" id="s_amount" class="form-control" step="0.01" readonly>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveSellLedgerChange();">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script>
      // XXX sell item change
    selllock=false;
    function sellLedgerChange(ele){
        data=$(ele).data('ledger');
        selldata=$(ele).data('data');
        console.log(data);
        $('#s_title').html(data.title);
        // debugger;
        $('#s_amount').val(selldata.total);
        // debugger;

        $('#id').val(data.id);
        // debugger;

        $('#s_rate').val(selldata.rate).change();
        $('#s_qty').val(selldata.qty).change();
        // debugger;

        $('#sellitem_change').modal('show');
    }

    function s_calculate(){
        $('#s_amount').val($('#s_rate').val()*$('#s_qty').val());
    }

    function saveSellLedgerChange(){
        if(!selllock){
            if(confirm('Do You Want TO Update Ledger ')){

                data={
                    id:$('#id').val(),
                    amount:$('#s_amount').val(),
                    rate:$('#s_rate').val(),
                    qty:$('#s_qty').val(),
                };

                selllock=true;
                axios.post("{{route('ledger.farmer.sellupdate')}}",data)
                .then(function(response){
                    console.log(response);
                    selllock=false;
                    $('#sellitem_change').modal('hide');
                    loadData();
                })
                .catch(function(err){
                    selllock=false;
                    alert('Ledger Cannot be Updated');
                })
            }
        }
    }

    function sellLedgerDelete(ele){
        data=$(ele).data('ledger');
        if(confirm("Do you want to delete ledger "+ data.title+"?")){
            selllock=true;
            axios.post("{{route('ledger.farmer.selldel')}}",{id:data.id})
                .then(function(response){
                    console.log(response);
                    selllock=false;
                    $('#sellitem_change').modal('hide');
                    loadData();
                })
                .catch(function(err){
                    selllock=false;
                    showNotification('bg-danger', 'You hove no authority!');
                })
        }
    }
  </script>
