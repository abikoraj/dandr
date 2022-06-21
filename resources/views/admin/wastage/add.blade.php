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
                        <div class="col-md-4">
                            <label for="date">Date</label>
                            <input name="date" type="text" class="form-control calender" id="add_date" required>
                        </div>
                        <div class="col-md-4">
                            <label for="item_id">Item</label>
                            <select name="item_id" id="item_id" class="form-control ms " required></select>
                        </div>


                        <div class="col-md-2">
                            <label for="amount">Qty</label>
                            <input name="amount" type="number" class="form-control" id="amount" step="0.001" required>
                        </div>
                        <div class="col-md-2">
                            <label for="center_id">Center</label>
                            <select name="center_id" id="add_center_id" class="ms form-control">
                                @foreach ($centers as $center)
                                    <option value="{{$center->id}}">{{$center->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 pt-2">
                        <button class="btn btn-primary">Add Wastage</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@section('js2')
<script>
    $('#addWastage').submit(function (e) {
        e.preventDefault();
        showProgress('Addming Wastage');
        const data=new FormData(this);
        axios.post(this.action,data)
        .then((res)=>{
            showNotification('bg-success',"Wastage added sucessfully");
            hideProgress();
            $('#item_id').val(null).change();
            $('#amount').val('');
            $('#addWastageModal').modal('hide');

        })
        .catch((err)=>{
            showNotification('bg-danger',"Wastage Cannot be added");
            hideProgress();

        })
    });
</script>
@endsection
