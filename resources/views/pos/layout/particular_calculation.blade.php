<div id="calc">
    <div class="row m-0">
      <div class="col-md-5 p-0 ">
          <div class="h-100 input-group">
              <table >
                <tr><td><label >Total</label></td><td><input type="number" readonly id="input-total" value="0"></td></tr>
                <tr><td><label >Discount</label></td><td><input type="number" id="input-discount" oninput="billpanel.calculateTotal();" class="f-sel" value="0"></td></tr>
                @if (env('companyUseTax',false))
                    <tr><td><label >Taxable</label></td><td><input type="number" readonly id="input-taxable" value="0"></td></tr>
                    <tr><td><label >Tax</label></td><td><input type="number" id='input-tax' oninput="billpanel.calculateTotal();" value="0" class="f-sel"></td></tr>
                @endif
              </table>
          </div>
      </div>
      <div class="col-6 col-md-2 p-0 ">
        <div id="total-container">
            <div id="total">
                <div class="text-center">
                  <label id="total-label">Grand Total</label>
                  <br>
                  <input id="input-grandtotal" value="0" readonly>
                </div>
            </div>
          </div>
          <div class="text-center text-white">
            <strong>
              Rounding
              <br>
              <span id="input-rounding">0</span>
            </strong>
          </div>
      </div>
      <div class="col-6 col-md-5 p-0 ">
        <div class="h-100 input-group">
          <table >
            <tr><td><label >Paid (f2) </label></td><td><input type="number"  id="input-paid" value="0" oninput="billpanel.calculateTotal();" class="f-sel" min="0"></td></tr>
            <tr><td><label >Due</label></td><td><input type="number" readonly id="input-due" value="0" min="0"></td></tr>
            <tr><td><label >Return</label></td><td><input type="number" readonly id="input-return" value="0" min="0"></td></tr>
          </table>
          <div class="row m-0">
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" id="btn-bill-save" onclick="billpanel.initSaveBill(false)" >Save (ctrl+s)</button>
            </div>
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" id="btn-bill-save-print"  onclick="billpanel.initSaveBill(true)">Save and Print (ctrl+p)</button>
            </div>
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" id="btn-bill-hold" onclick="holdBillPanel.init();">Hold Bill (ctrl+h)</button>
            </div>
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" id="btn-bill-cancel"  onclick="billpanel.cancelBill()" >Cancel Bill (ctrl+c)</button>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>
