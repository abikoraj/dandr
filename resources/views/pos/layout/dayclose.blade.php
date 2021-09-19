<div class="modal fade" id="dayclose" tabindex="-1" aria-labelledby="daycloselabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >Day Close</h5>
          <hr>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div>
                <strong>Current Amount:</strong> <span id="current-amount">0</span>
            </div>
            <hr>
            <form id="dayclose-form" onsubmit="return cancelSubmit(event)">
                <div class="row">
                    <div class="col-md-4">Rs. 1000</div><div class="col-md-8"><input id="closing-amount-1000" type="number" min="0" class="form-control" oninput="calculateClosingAmount()"  required></div>
                    <div class="col-md-4">Rs. 500</div><div class="col-md-8"><input id="closing-amount-500" type="number" min="0" class="form-control" oninput="calculateClosingAmount()"  required></div>
                    <div class="col-md-4">Rs. 100</div><div class="col-md-8"><input id="closing-amount-100" type="number" min="0" class="form-control" oninput="calculateClosingAmount()"  required></div>
                    <div class="col-md-4">Rs. 50</div><div class="col-md-8"><input id="closing-amount-50" type="number" min="0" class="form-control" oninput="calculateClosingAmount()"  required></div>
                    <div class="col-md-4">Rs. 20</div><div class="col-md-8"><input id="closing-amount-20" type="number" min="0" class="form-control" oninput="calculateClosingAmount()"  required></div>
                    <div class="col-md-4">Rs. 10</div><div class="col-md-8"><input id="closing-amount-10" type="number" min="0" class="form-control" oninput="calculateClosingAmount()"  required></div>
                    <div class="col-md-4">Rs. 5</div><div class="col-md-8"><input id="closing-amount-5" type="number" min="0" class="form-control" oninput="calculateClosingAmount()" required></div>
                    <div class="col-md-4">Rs. 2</div><div class="col-md-8"><input id="closing-amount-2" type="number" min="0" class="form-control" oninput="calculateClosingAmount()" required></div>
                    <div class="col-md-4">Rs. 1</div><div class="col-md-8"><input id="closing-amount-1" type="number" min="0" class="form-control" oninput="calculateClosingAmount()" required></div>
                
                </div>
            </form>
            <hr>
            <div>
                <strong>Closing Amount:</strong> <span id="closing-amount">0</span>
            </div>
            <hr>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel (esc)</button>
          <button type="button" class="btn btn-primary" onclick="billpanel.closeCounter()">Close Counter </button>
        </div>
      </div>
    </div>
  </div>