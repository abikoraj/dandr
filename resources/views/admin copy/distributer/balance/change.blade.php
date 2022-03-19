<div class="modal fade" id="change" tabindex="-1" role="dialog" aria-labelledby="changemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Ledger</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="title">

            </div>
            <hr>
            <div class="row">
                <input type="hidden"  id="id">
                <div class="col-md-6">
                    <label for="amount">amount</label>
                    <input type="number" name="amount" id="amount" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="l_type">type</label>
                    <select  id="l_type" class="show-tick ms select2 form-control">
                        <option value="1">CR</option>
                        <option value="2">DR</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveLedgerChange();">Save changes</button>
        </div>
      </div>
    </div>
  </div>
