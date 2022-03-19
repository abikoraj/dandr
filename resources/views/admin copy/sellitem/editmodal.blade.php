<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-ff="eu_id">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Edit Sell Item</h4>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">X</button>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="editform">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <input type="hidden" id="eid" name="id">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="text" name="date" id="enepali-datepicker" class="calender form-control next" data-next="eu_id" placeholder="Date" readonly>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unumber">User Number</label>
                                    <input type="number" name="user_id" id="eu_id" placeholder="User number" class="form-control checkfarmer next" data-next="eitem_id" min="1" >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <!-- <input type="hidden" name=""> -->
                                    <label for="unumber">Item Number</label>
                                    <input type="text" id="eitem_id" name="number" placeholder="Item number" class="form-control checkitem next" data-next="erate" min="1">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="rate">Rate</label>
                                <input type="number" name="rate" onkeyup="calTotal(); paidTotal();" id="erate" step="0.001" value="0" placeholder="Item rate" class="form-control next" data-next="eqty" min="0.001">
                            </div>

                            <div class="col-md-3">
                                <label for="qty">Quantity</label>
                                <input type="number" name="qty" id="eqty" onkeyup="calTotal(); paidTotal();" step="0.001" value="1" placeholder="Item quantity" class="form-control next" data-next="etotal" min="0.001">
                            </div>

                            <div class="col-md-3">
                                <label for="total">Total</label>
                                <input type="number" name="total" id="etotal" step="0.001" placeholder="Total" value="0" class="form-control next connectmax" data-connected="epaid" data-next="epaid" min="0.001" readonly>
                            </div>

                            <div class="col-md-3">
                                <label for="paid">Paid</label>
                                <input type="number" name="paid" onkeyup="paidTotal();" id="epaid" step="0.001" placeholder="Total" value="0" class="form-control next" data-next="edue" min="0.001">
                            </div>

                            <div class="col-md-3">
                                <label for="due">Due</label>
                                <input type="number" name="due" id="edue" min="0" step="0.001" placeholder="due" value="0" class="form-control next" data-next="udata" min="0" readonly>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end mt-3">
                                <input type="button" class="btn btn-primary btn-block" onclick="udateData();" id="udata" value="Update Data">
                                {{-- <span >Update Data</span> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
