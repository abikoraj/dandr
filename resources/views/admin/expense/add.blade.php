<div class="modal fade" id="addModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Add Expense</h4>
            </div>
            <hr>
            <div id="content" class="p-2">
            </div>
        </div>
    </div>
</div>

<div class="collapse mb-3 " id="collapseExample">
    <div class="shadow p-3">

        <form id="form_validation" method="POST" onsubmit="return saveData(event,this);">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <label for="name">Date</label>
                    <div class="form-group">
                        <input type="text" name="date" id="nepali-datepicker" class="calender form-control next calender" data-next="cat_id"
                            placeholder="Date" required>
                    </div>
                </div>
        
                <div class="col-lg-4">
                    <label for="name">Expense Category</label>
                    <div class="form-group">
                        <select name="cat_id" id="cat_id" class="form-control show-tick ms select2 next" data-next="title"
                            data-placeholder="Select" required>
                            <option></option>
                            @foreach (\App\Models\Expcategory::get() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="col-lg-4">
                    <label for="name">Title</label>
                    <div class="form-group">
                        <input type="text" id="title" name="title" class="form-control next" data-next="amount"
                            placeholder="Enter expense title" required>
                    </div>
                </div>
        
                <div class="col-lg-4">
                    <label for="name">Amount</label>
                    <div class="form-group">
                        <input type="number" id="amount" name="amount" min="0" class="form-control next xpay_handle" data-next="paid_by"
                            placeholder="Enter expense amount" required>
                    </div>
                </div>
        
                <div class="col-lg-4">
                    <label for="name">Paid By</label>
                    <div class="form-group">
                        <input type="text" id="paid_by" name="payment_by" class="form-control next" data-next="payd"
                            placeholder="Enter name of paiyer" required>
                    </div>
                </div>
        
                <div class="col-lg-4">
                    <label for="name">Payment Detail</label>
                    <div class="form-group">
                        <input type="text" id="payd" name="payment_detail" class="form-control next" data-next="remark"
                            placeholder="Enter payment detail" required>
                    </div>
                </div>
        
                <div class="col-lg-12">
                    <label for="remark">Remarks</label>
                    <div class="form-group">
                        <input type="text" id="remark" name="remark" class="form-control" placeholder="Enter remark"
                            required>
                    </div>
                </div>
                @include('admin.payment.take',['xpay_type'=>2])
                <div class="col-lg-12 ">
                    <div class="form-group" >
                        <button class="btn btn-primary ">Submit Data</button>
                        {{-- <span class="btn btn-danger"  data-dismiss="modal">Cancel</span> --}}
                        <span class="btn btn-danger mr-1" id="close-add" data-toggle="collapse" data-target="#collapseExample"  >Cancel </span>
    
                        <input type="checkbox"  id="add_another"> <span>Add Another</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
