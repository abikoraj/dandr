<!-- add modal -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="iname">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New Item</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="add-bill" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Item Name</label>
                                <div class="form-group">
                                    <input type="text" id="iname" name="name" class="form-control next" data-next="inum" placeholder="Enter item name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Item Number / Barcode</label>
                                <div class="form-group">
                                    <input type="text" id="inum" name="number" class="form-control next" data-next="cprice" placeholder="Enter unique item number" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="cprice">Cost Price</label>
                                <div class="form-group">
                                    <input type="number" id="cprice" name="cost_price" min="0" class="form-control next" data-next="sprice" placeholder="Enter cost price" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="sprice">Sell Price</label>
                                <div class="form-group">
                                    <input type="number" id="sprice" name="sell_price" min="0" class="form-control next" data-next="stock" placeholder="Enter sell price" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="stock">Stock</label>
                                <div class="form-group">
                                    <input type="number" id="stock" name="stock" min="0" class="form-control next" data-next="unit" placeholder="Enter stock" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="unit">Unit Type</label>
                                <div class="form-group">
                                    <input type="text" id="unit" name="unit" class="form-control next" data-next="reward" placeholder="Enter unit type" required>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="unit">Reward (%)</label>
                                <div class="form-group">
                                    <input type="number" id="reward" name="reward" step="0.001" min="0" value="0" class="form-control" placeholder="Enter item reward percentage" >
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <input type="checkbox" name="trackstock" id="trackstock" value="1">
                                <label for="trackstock">Track Stock</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="checkbox" name="trackexpiry" id="trackexpiry" value="1">
                                <label for="trackexpiry">Track Expiry</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="checkbox" name="sellonline" id="sellonline" value="1">
                                <label for="sellonline">Sell Online</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="checkbox" name="disonly" id="disonly" value="1">
                                <label for="disonly">Sell Distributor</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="checkbox" name="posonly" id="posonly" value="1">
                                <label for="posonly">Sell POS</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="checkbox" name="farmeronly" id="farmeronly" value="1">
                                <label for="farmeronly">Sell Farmer</label>
                            </div>
                            <div class="col-lg-3">
                                <input type="checkbox" name="taxable" id="taxable" value="1">
                                <label for="taxable">Taxable</label>
                            </div>
                            <div class="col-lg-12"></div>
                            <div class="col-lg-3">
                                <label for="tax">Tax/VAT</label>
                                <div class="form-group">
                                    <input type="number" id="tax" name="tax" step="0.001" min="0" value="13" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="expirydays">Expiary Days</label>
                                <div class="form-group">
                                    <input type="number" id="expirydays" name="expirydays" step="0.001" min="0" value="0" class="form-control" >
                                </div>
                            </div>
                           
                            <div class="col-lg-3">
                                <label for="minqty">Min Online Qty</label>
                                <div class="form-group">
                                    <input type="number" id="minqty" name="minqty" step="0.001" min="0" value="0" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="dis_number">Distributer Number</label>
                                <div class="form-group">
                                    <input type="text" id="dis_number" name="dis_number"  class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="dis_price">Distributer Rate</label>
                                <div class="form-group">
                                    <input type="number" id="dis_price" name="dis_price" step="0.001" min="0" value="0" class="form-control" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                        <label for="image">Image</label>
                                        <div class="form-group">
                                            <input type="file" id="image" name="image" accept="image/*" class="form-control" >
                                        </div>
                            </div>
                            <div class="col-lg-9">
                                <label for="description">Description</label>
                                <div class="form-group">
                                    <textarea id="description" name="description"  class="form-control" ></textarea>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>