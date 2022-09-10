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
                                    <div class="row">
                                        @if (env('usecc',0)==1)

                                        <div class="col-6 ck">

                                            <input type="checkbox"  id="eusecc" name="usecc" class="mr-2" value="1">Has Cooling Cost <br>
                                        </div>
                                        @endif
                                        @if (env('usetc',0)==1)

                                        <div class="col-6 ck">

                                            <input type="checkbox" id="eusetc" name="usetc" class="mr-2" value="1">Has TS % <br>
                                        </div>
                                        @endif
                                        <div class="col-6 ck">

                                            <input type="checkbox" id="euserate"  name="userate" class="mr-2" value="1">Fixed Rate
                                        </div>
                                        <div class="col-6">

                                            <input type="number" step="0.01" min="0" value="0" id="ef_rate" name="f_rate"> <br>
                                        </div>
                                        @if (env('usetc',0)==1)

                                        <div class="col-6 ck">

                                            <input type="checkbox" id="euse_ts_amount"  name="use_ts_amount" class="mr-2" value="1">Fixed TS Rate
                                        </div>
                                        <div class="col-6">

                                            <input type="number" step="0.01" min="0" value="0" id="ets_amount" name="ts_amount">
                                        </div>
                                        @endif
                                        @if (env('useprotsahan',0)==1)

                                            <div class="col-6 ck">

                                                <input type="checkbox" id="euse_protsahan"  name="use_protsahan" class="mr-2" value="1"> Protsahan Amount
                                            </div>
                                            <div class="col-6">

                                                <input type="number" step="0.01" min="0" value="0" id="eprotsahan" name="protsahan">
                                            </div>
                                        @endif
                                        @if (env('usetransportamount',0)==1)
                                            
                                            <div class="col-6 ck">

                                                <input type="checkbox" id="euse_transport"  name="use_transport" class="mr-2" value="1"> Transport Amount
                                            </div>
                                            <div class="col-6">

                                                <input type="number" step="0.01" min="0" value="0" id="etransport" name="transport">
                                            </div>
                                        @endif
                                    </div>
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