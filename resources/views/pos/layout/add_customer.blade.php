<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Customer</h4>
            </div>
            <hr>
            <div class="card p-2">
                <div class="body">
                    <form id="addCustomer" method="POST" onsubmit="return billpanel.saveCustomer(event,this);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Customer Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter customer name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Customer Phone</label>
                                <div class="form-group">
                                    <input type="number" id="phone" name="phone" class="form-control next" data-next="address" placeholder="Enter customer phone" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="name">Customer Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control " data-next="rate" placeholder="Enter customer address" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <label for="amount">Opening Balance</label>
                                <div class="form-group">
                                    <input type="number" value="0" id="amount" name="amount" min="0" step="0.01" class="form-control next" data-next="address" placeholder="Enter Current Balance" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="amounttype">Balance Type</label>
                                <div class="form-group">
                                    <select name="amounttype" id="amounttype" class="form-control show-tick ms">
                                        <option value="1">CR</option>
                                        <option value="2">DR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal" onclick="$('#addCustomerModal').modal('hide');">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>