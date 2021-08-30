<div id="calc">
    <div class="row m-0">
      <div class="col-md-5 p-0 ">
          <div class="h-100 input-group">
              <table >
                <tr><td><label >Total</label></td><td><input type="number" readonly id="input-total" value="0"></td></tr>
                <tr><td><label >Discount</label></td><td><input type="number" id="input-discount" oninput="billpanel.calculateTotal();" class="f-sel" value="0"></td></tr>
                <tr><td><label >Taxable</label></td><td><input type="number" readonly id="input-taxable" value="0"></td></tr>
                <tr><td><label >Tax</label></td><td><input type="number" id='input-tax' oninput="billpanel.calculateTotal();" value="0" class="f-sel"></td></tr>
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
      </div>
      <div class="col-6 col-md-5 p-0 ">
        <div class="h-100 input-group">
          <table >
            <tr><td><label >Paid</label></td><td><input type="number"  id="input-paid" value="0" oninput="billpanel.calculateTotal();" class="f-sel"></td></tr>
            <tr><td><label >Due</label></td><td><input type="number" readonly id="input-due" value="0"></td></tr>
            <tr><td><label >Return</label></td><td><input type="number" readonly id="input-return" value="0"></td></tr>
          </table>
          <div class="row m-0">
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" onclick="billpanel.saveBill(false)">Save</button>
            </div>
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" onclick="billpanel.saveBill(true)">Save and Print</button>
            </div>
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100">Hold Bill</button>
            </div>
            <div class="col-md-6 p-0">
              <button class="btn-bill w-100" onclick="billpanel.cancelBill()">Cancel Bill</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>