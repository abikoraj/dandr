<div class="item-panel  pt-2  px-2 mb-3">
    <div class="row">
      <div class="col-md-12">
          <div class="form-group">
            <label for="item-name" class="mb-1 text-white">Name</label>
              <input type="text" id="item-name" class="form-control">
            {{-- <select id="item-name"  class="form-control" onchange="billpanel.setRate();"></select> --}}
          </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="item-rate" class="mb-1 text-white">Rate</label>
          <input id="item-rate" type="number" readonly  class="form-control">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="item-qty" class="mb-1 text-white">Qty</label>
          <input id="item-qty" type="number" min="0"  class="form-control">
        </div>
      </div>
      <div class="col-md-12 py-2">
        <button class="btn btn-primary btn-block" onclick="billpanel.addItemSelect()">Add To Bill</button>
      </div>
    </div>
</div>