<div class="item-panel p-2">
    <div class="row m-0 mb-2">
        <div class=" p-1 col-md-6">
            <div class="form-group">
                <input type="radio" name="radio_customer_search" checked value="1"> Phone
            </div>
        </div>
        <div class=" p-1 col-md-6">
            <div class="form-group">
                <input type="radio" name="radio_customer_search" value="2"> Name
            </div>
        </div>
        <div class=" p-1 col-md-9">
          <input type="text" class="form-control" id="input_customer_search">
        </div>
        <div class=" p-1 col-md-3">
          <button class="btn btn-primary w-100" onclick="billpanel.customerSearch()">Search</button>
        </div>
    </div>

    <div id="customer_data"> 

    </div>
</div>
