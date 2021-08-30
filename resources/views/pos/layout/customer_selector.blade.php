<div class="item-panel p-2 mb-2 d-none" id="cutomer-search-panel">
    <div class="row m-md-0  mb-2">
        <div class=" p-1 col-md-12 ">
            <div class="form-group d-flex justify-content-between align-item-center">
                <span>

                    <input type="radio" name="radio_customer_search"  value="1"> <label for="" class="text-white me-3">Phone</label>  
                    <input type="radio" name="radio_customer_search" checked value="2"> <label for="" class="text-white">Name</label>
                </span>
                <span>
                    <button class="btn btn-danger" onclick="billpanel.closeCusSearch()">Close</button>
                </span>
            </div>
        </div>
      
        <div class=" p-1 col-md-9">
          <input type="text" class="form-control" id="input_customer_search">
        </div>
        <div class=" p-1 col-md-3">
          <button class="btn btn-primary w-100" onclick="billpanel.customerSearch()">Search</button>
        </div>
    </div>

    <div id="customer_list"> 

    </div>
</div>
<div class="item-panel p-2" id="customer-input-panel">
    <h4 class="text-white d-flex justify-content-between">
        <span>Customer</span>
        <span>
            <button class="btn btn-primary" onclick="customerSearchInit()">Search</button>
            <button class="btn btn-primary" onclick="$('#addCustomerModal').modal('show');">Add Customer</button>
        </span>
    </h4>
    <hr>
    <table class="w-100">
        <tr>
            <th class="text-end pe-3 text-white">Name</th>
            <td>
                <input class="form-control" type="text" name="customer-name" id="customer-name" readonly>
            </td>
        </tr>
        <tr>
            <th class="text-end pe-3 text-white">Address</th>
            <td>
                <input class="form-control" type="text" name="customer-address" id="customer-address" readonly>
            </td>
        </tr>
        <tr>
            <th class="text-end pe-3 text-white">Phone</th>
            <td>
                <input class="form-control" type="text" name="customer-phone" id="customer-phone" readonly>
            </td>
        </tr>
        <tr>
            <th class="text-end pe-3 text-white">PAN/VAT No</th>
            <td>
                <input class="form-control" type="text" name="customer-panvat" id="customer-panvat">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="p-1 text-end ">
                <button class="btn btn-danger btn-sm" onclick="billpanel.resetCustomer()">Clear</button>

            </td>
        </tr>
    </table>
</div>
@include('pos.layout.add_customer')