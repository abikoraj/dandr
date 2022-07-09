<!-- edit modal -->


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-ff="eamount">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Edit Advance</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="editform" onsubmit="return editData(event);">
                        @csrf
                        <input type="hidden" name="id" id="eid">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="amount">Advance Amount</label>
                                <input type="number" id="eamount" min="0" name="amount" class="form-control expay_handle" placeholder="Enter advance amount" value="0" required>
                            </div>
                            <div class="col-lg-6 pt-4">
                                <button class="btn btn-raised btn-primary waves-effect btn-block" type="submit">Submit Data</button>
                            </div>

                            @include('admin.payment.editholder')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
