<!--add modal -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="center_id">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Farmer</h4>
            </div>
            <hr>
            <div class="card mb-0">
                <div class="body">
                    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <input type="hidden" value="1" name="is_farmer">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Collection Center</label>
                                    <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="name" required>
                                        <option value="-1">Select A Center</option>
                                        @foreach(\App\Models\Center::all() as $c)
                                        <option value="{{$c->id}}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6" id="noid">
                                 <label for="farmer_no">Farmer No</label>
                                 <div class="form-group">
                                     <input type="text" id="farmer_no" name="farmer_no" class="form-control next" data-next="name" placeholder="Enter farmer no" required>
                                 </div>
                             </div>
                            <div class="col-lg-6">
                                <label for="name">Farmer Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="{{env('requirephone',1)==1?'phone':'address'}}" placeholder="Enter farmer name" required>
                                </div>
                            </div>
                            @if(env('requirephone',1)==1)
                                <div class="col-lg-6">
                                    <label for="name">Farmer Phone</label>
                                    <div class="form-group">
                                        <input type="number" id="phone" name="phone" class="form-control next" data-next="address" placeholder="Enter farmer phone" required>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-6">
                                <label for="name">Farmer Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control "  placeholder="Enter farmer address" required>
                                </div>
                            </div>

                            {{-- <div class="col-lg-6">
                                <label for="name">Advance Amount </label>
                                <div class="form-group">
                                    <input type="number" id="advance" name="advance" step="0.001" value="0" class="form-control" placeholder="Enter advance">
                                </div>
                            </div> --}}
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <input type="checkbox" name="usecc" class="mr-2" value="1">Has Cooling Cost <br>
                                    <input type="checkbox" name="usetc" class="mr-2" value="1">Has TS <br>
                                    <input type="checkbox"  name="userate" class="mr-2" value="1">Fixed Rate
                                    <input type="number" step="0.01" min="0" value="0" name="f_rate">
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">

                <button class="btn btn-raised btn-primary waves-effect" type="submit" >Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
            </div>
            </form>
            <div class="text-right pr-3">
                <span>
                    <input type="checkbox" id="another"> Add Another
                </span>
                <span>
                    <input onchange="changeAutoIncrement(this);" type="checkbox" id="auto"> Auto Increment
                </span>
            </div>
        </div>
    </div>
</div>
