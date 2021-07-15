<!-- edit modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-ff="ename">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Edit Farmer</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="editform" onsubmit="return editData(event);">
                        @csrf
                        <input type="hidden" name="id" id="eid">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Farmer No</label>
                                <div class="form-group">
                                    <input type="text" id="eno" name="no" class="form-control next" data-next="ename" placeholder="Enter farmer no" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">Farmer Name</label>
                                <div class="form-group">
                                    <input type="text" id="ename" name="name" class="form-control next" data-next="{{env('requirephone',1)==1?'ephone':'eaddress'}}" placeholder="Enter farmer name" required>
                                </div>
                            </div>

                            @if(env('requirephone',1)==1)
                            <div class="col-lg-6">
                                <label for="name">Farmer Phone</label>
                                <div class="form-group">
                                    <input type="number" id="ephone" name="phone" class="form-control next" data-next="eaddress" placeholder="Enter farmer phone" required>
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-6">
                                <label for="name">Farmer Address</label>
                                <div class="form-group">
                                    <input type="text" id="eaddress" name="address" value="" class="form-control" placeholder="Enter farmer address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <input type="checkbox" id="eusecc" name="usecc" class="mx-2" value="1">Has Cooling Cost <br>
                                    <input type="checkbox" id="eusetc" name="usetc" class="mx-2" value="1">Has TS <br>
                                    <input type="checkbox" id="euserate" name="userate" class="mx-2" value="1">Fixed Rate
                                    <input type="number" min="0" step="0.01" value="0" name="rate" id="erate">
                                </div>
                            </div>

                            <!-- <div class="col-lg-6">
                                <label for="name">Advance Amount </label>
                                <div class="form-group">
                                    <input type="number" id="eadvance" name="advance" step="0.001" value="0" class="form-control" placeholder="Enter advance">
                                </div>
                            </div> -->
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