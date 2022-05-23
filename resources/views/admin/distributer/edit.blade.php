<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-ff="ename">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Edit Distributer</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="editform" onsubmit="return editData(event);">
                        @csrf
                        <input type="hidden" name="id" id="eid">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Distributer Name</label>
                                <div class="form-group">
                                    <input type="text" id="ename" name="name" class="form-control next" data-next="ephone" placeholder="Enter distributer name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Distributer Phone</label>
                                <div class="form-group">
                                    <input type="number" id="ephone" name="phone" class="form-control next" data-next="eaddress" placeholder="Enter distributer phone" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="name">Distributer Address</label>
                                <div class="form-group">
                                    <input type="text" id="eaddress" name="address" value="" class="form-control next" data-next="erate" placeholder="Enter distributer address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="ecredit_days">Credit Days</label>
                                <div class="form-group">
                                    <input type="number" id="ecredit_days" name="credit_days" min="0" step="0.01" class="form-control next" data-next="address" placeholder="Enter Credit Days" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="ecredit_limit">Credit Limit</label>
                                <div class="form-group">
                                    <input type="number" id="ecredit_limit" name="credit_limit" min="0" step="0.01" class="form-control next" data-next="address" placeholder="Enter Credit Limit" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="snf_rate">
                                    SNF Rate
                                </label>
                                <input type="text" name="snf_rate" id="esnf_rate" class="form-control next" data-next="efat_rate">
                            </div>
                            <div class="col-md-3">
                                <label for="fat_rate">
                                    Fat Rate
                                </label>
                                <input type="text" name="fat_rate" id="efat_rate" class="form-control next" data-next="eadded_rate">
                            </div>
                            <div class="col-md-3">
                                <label for="added_rate">
                                    Added Rate (per ℓ)
                                </label>
                                <input type="text" name="added_rate" id="eadded_rate" class="form-control next" data-next="fat_rate">
                            </div>
                            <div class="col-md-3 ">
                                <label for="f_rate">
                                    <input type="checkbox" id="eis_fixed" name="is_fixed" class="mr-2 " value="1" class="" data-switch="#efixed_rate">Fixed Rate
                                </label>
                                <input type="number" step="0.01" min="0" placeholder="Milk Rate"  name="fixed_rate" id="efixed_rate" class="form-control">
                            </div>
                            {{-- <div class="col-lg-6">
                                <label for="rate">Rate</label>
                                <div class="form-group">
                                    <input type="number" id="erate" name="rate" step="0.001" min="0.001" class="form-control next" data-next="eamt" placeholder="Enter rate" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="amt">Amount(Qty)</label>
                                <div class="form-group">
                                    <input type="number" id="eamt" name="amount" step="0.001" min="0.001" class="form-control" placeholder="Enter amount" required>
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
