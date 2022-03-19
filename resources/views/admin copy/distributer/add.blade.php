<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Distributer</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Distributer Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter distributer name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Distributer Phone</label>
                                <div class="form-group">
                                    <input type="number" id="phone" name="phone" class="form-control next" data-next="address" placeholder="Enter distributer phone" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="name">Distributer Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control " data-next="rate" placeholder="Enter distributer address" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <label for="credit_days">Credit Days</label>
                                <div class="form-group">
                                    <input type="number" id="credit_days" name="credit_days" min="0" step="0.01" class="form-control next" data-next="address" placeholder="Enter Credit Days" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="credit_limit">Credit Limit</label>
                                <div class="form-group">
                                    <input type="number" id="credit_limit" name="credit_limit" min="0" step="0.01" class="form-control next" data-next="address" placeholder="Enter Credit Days" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="snf_rate">
                                    SNF Rate
                                </label>
                                <input type="text" name="snf_rate" id="snf_rate" class="form-control next" data-next="fat_rate">
                            </div>
                            <div class="col-md-3">
                                <label for="fat_rate">
                                    Fat Rate
                                </label>
                                <input type="text" name="fat_rate" id="fat_rate" class="form-control next" data-next="added_rate">
                            </div>
                            <div class="col-md-3">
                                <label for="added_rate">
                                    Added Rate (per ℓ)
                                </label>
                                <input type="text" name="added_rate" id="added_rate" class="form-control next" data-next="fat_rate">
                            </div>
                            <div class="col-md-3 ">
                                <label for="f_rate">
                                    <input type="checkbox" name="is_fixed" class="mr-2 switch" value="1" class="" data-switch="#d_fixed_rate">Fixed Rate
                                </label>
                                <input type="number" step="0.01" min="0" placeholder="Milk Rate" name="fixed_rate" id="d_fixed_rate" class="form-control">
                            </div>

                            {{-- <div class="col-lg-6">
                                <label for="rate">Rate</label>
                                <div class="form-group">
                                    <input type="number" id="rate" name="rate" step="0.001" min="0.001" class="form-control next" data-next="amt" placeholder="Enter rate" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="amt">Amount(Qty)</label>
                                <div class="form-group">
                                    <input type="number" id="amt" name="amount" step="0.001" min="0.001" class="form-control" placeholder="Enter amount" required>
                                </div>
                            </div> --}}
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