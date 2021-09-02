<div class="modal fade" id="payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="payment">Payment Method</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="payment-form" onsubmit="return cancelSubmit(event)">
              <div>
                <span class="me-2">
                    <input name="payment-type" id="payment-type-1" type="radio" value="0" class="me-1 payment-type" checked> Cash |
                </span>
                <span class="me-2">
                    <input name="payment-type" id="payment-type-2" type="radio" value="1" class="me-1 payment-type" > Card |
                </span>
                <span class="me-2">
                    <input name="payment-type" id="payment-type-3" type="radio" value="2" class="me-1 payment-type" > Cheque |
                </span>
                <span class="me-2">
                    <input name="payment-type" id="payment-type-4" type="radio" value="3" class="me-1 payment-type" > Online |
                </span>
              </div>
              <hr>
              <div class="payment-input payment-input-1 ">
                <label for="bank">Bank</label>
                <select name="bank" id="bank" class="form-control">
                  @foreach ($banks as $bank)
                      <option value="{{$bank->id}}">{{$bank->name}} - {{$bank->accno}}</option>
                  @endforeach
                </select>
              </div>
              <div class="payment-input payment-input-1 ">
                <label for="cardno">Card No</label>
                <input type="text" name="cardno" id="cardno" class="form-control">
              </div>
              <div class="payment-input payment-input-2 ">
                <label for="bank-name">Bank Name</label>
                <input type="text" name="bank-name" id="bank-name" class="form-control">
              </div>
              <div class="payment-input payment-input-2 ">
                <label for="chequeno">Cheque No</label>
                <input type="text" name="chequeno" id="chequeno" class="form-control">
              </div>
              <div class="payment-input payment-input-3 ">
                <label for="geteway">Payment GateWay</label>
                <select name="gateway" id="gateway" class="form-control">
                  @foreach ($gateways as $gateway)
                      <option value="{{$gateway->id}}">{{$gateway->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="payment-input payment-input-3 payment-input-1 ">
                <label for="txnno">Transaction No</label>
                <input type="text" name="txnno" id="txnno" class="form-control">
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="billpanel.saveBill()">Save Bill</button>
        </div>
      </div>
    </div>
  </div>

  @section('js1')
      <script>
        $('.payment-type').change(function(){
          console.log(this.value);
          $('.payment-input').addClass('d-none');
          $('.payment-input-'+this.value).removeClass('d-none');
        });
        $('.payment-input').addClass('d-none');

      </script>
  @endsection