<div class="modal fade" id="pay_change" tabindex="-1" role="dialog" aria-labelledby="pay_changemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Ledger - Distrinutor Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="p_title">

            </div>
            <hr>
            <div class="row">
                <input type="hidden"  id="id">

                <div class="col-md-6">
                    <label for="s_total">Payment Amount</label>
                    <input type="number" name="p_amount" id="p_amount" class="form-control" step="0.01" >
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savePayLedgerChange();">Save changes</button>
        </div>
      </div>
    </div>
  </div>
