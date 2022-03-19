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
