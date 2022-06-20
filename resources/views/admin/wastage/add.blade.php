@section('toobar')
    <button class="btn btn-primary" data-toggle="modal" data-target="#addWastageModal">Add New</button>
@endsection

<div id="addWastageModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Add Wastage</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.wastage.add')}}" id="addWastage">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="item_id">Item</label>
                            <select name="item_id" id="item_id" class="form-control ms " ></select>
                        </div>

                        <div class="col-md-3">
                            <label for="amount">Amount</label>
                            <input name="amount" type="number" class="form-control" id="amount" step="0.001" >
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3"></div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
